<?php

namespace App\Http\Controllers\Gateway;

use App\GeneralSetting;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GatewayCurrency;
use App\Deposit;
use App\Owner;
use App\SoldPackage;
use Session;

class PaymentController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }

    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $page_title = 'Payment Methods';
        return view('owner.payment.deposit', compact('gatewayCurrency', 'page_title'));
    }

    public function depositInsert(Request $request)
    {
        $request->validate([
            'method_code' => 'required',
            'currency' => 'required',
        ]);

        $owner   = auth()->guard('owner')->user();
        $order  = SoldPackage::where('order_number', session('order_number'))->first();

        if($order->payment_status == 1){
            $notify[] = ['error', 'You have already paid for this order'];
            return redirect('/')->withNotify($notify);
        }

        $now    = \Carbon\Carbon::now();

        if (session()->has('req_time') && $now->diffInSeconds(\Carbon\Carbon::parse(session('req_time'))) <= 2) {
            $notify[] = ['error', 'Please wait a moment, processing your deposit'];
            return redirect()->route('owner.deposit.preview')->withNotify($notify);
        }
        session()->put('req_time', $now);

        $gate   = GatewayCurrency::where('method_code', $request->method_code)->where('currency', $request->currency)->first();


        if (!$gate) {
            $notify[] = ['error', 'Invalid Gateway'];
            return back()->withNotify($notify);
        }

        if($gate->min_amount > $order->price || $gate->max_amount < $order->price) {
            $notify[] = ['error', 'Please Follow Deposit Limit'];
            return back()->withNotify($notify);
        }

        $charge     = getAmount($gate->fixed_charge + ($order->price * $gate->percent_charge / 100));
        $payable    = getAmount($order->price + $charge);
        $final_amo  = getAmount($payable * $gate->rate);

        $depo['owner_id'] = $owner->id;
        $depo['sold_package_id'] = $order->id;
        $depo['method_code'] = $gate->method_code;
        $depo['method_currency'] = strtoupper($gate->currency);
        $depo['amount'] = $order->price;
        $depo['charge'] = $charge;
        $depo['rate'] = $gate->rate;
        $depo['final_amo'] = getAmount($final_amo);
        $depo['btc_amo'] = 0;
        $depo['btc_wallet'] = "";
        $depo['trx'] = getTrx();
        $depo['try'] = 0;
        $depo['status'] = 0;
        $data = Deposit::create($depo);

        Session::put('Track', $data['trx']);
        return redirect()->route('owner.deposit.preview');
    }


    public function depositPreview()
    {
        $track = Session::get('Track');
        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->firstOrFail();
        if (is_null($data)) {
            $notify[] = ['error', 'Invalid Deposit Request'];
            return redirect()->route('owner.deposit')->withNotify($notify);
        }
        if ($data->status != 0) {
            $notify[] = ['error', 'Invalid Deposit Request'];
            return redirect()->route('owner.deposit')->withNotify($notify);
        }


        $page_title = 'Payment Preview';
        return view('owner.payment.preview', compact('data', 'page_title'));
    }


    public function depositConfirm()
    {
        $track = Session::get('Track');
        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->with('gateway')->first();
        if (is_null($deposit)) {
            $notify[] = ['error', 'Invalid Deposit Request'];
            return redirect()->route('owner.deposit')->withNotify($notify);
        }
        if ($deposit->status != 0) {
            $notify[] = ['error', 'Invalid Deposit Request'];
            return redirect()->route('owner.deposit')->withNotify($notify);
        }

        if ($deposit->method_code >= 1000) {
            $this->userDataUpdate($deposit);
            $notify[] = ['success', 'Your deposit request is queued for approval.'];
            return back()->withNotify($notify);
        }


        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';
        $data = $new::process($deposit);
        $data = json_decode($data);

        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return redirect()->route('owner.deposit')->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if(@$data->session){
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $page_title = 'Payment Confirm';


        return view($data->view, compact('data', 'page_title', 'deposit'));
    }


    public static function userDataUpdate($trx)
    {
        $gnl    = GeneralSetting::first();
        $data   = Deposit::where('trx', $trx)->first();
        if ($data->status == 0) {
            $owner          = Owner::find($data->owner_id);
            $data['status'] = 1;
            $owner->balance += $data->amount;

            $owner->save();
            $data->update();

            $gateway                    = $data->gateway;
            $transaction                = new Transaction();
            $transaction->owner_id      = $data->owner_id;
            $transaction->amount        = $data->amount;
            $transaction->post_balance  = getAmount($owner->balance);
            $transaction->charge        = getAmount($data->charge);
            $transaction->trx_type      = '+';
            $transaction->details       = 'Deposit Via ' . $gateway->name;
            $transaction->trx           = $data->trx;
            $transaction->save();

            $order                      = SoldPackage::where('id', $data->sold_package_id)->with('package')->first();
            $order->status              = 1;
            $order->save();

            notify($owner, 'DEPOSIT_COMPLETE', [
                'method_name'       => $data->gateway_currency()->name,
                'package'           => $order->package->name,
                'method_currency'   => $data->method_currency,
                'method_amount'     => getAmount($data->final_amo),
                'amount'            => getAmount($data->amount),
                'charge'            => getAmount($data->charge),
                'currency'          => $gnl->cur_text,
                'rate'              => getAmount($data->rate),
                'trx'               => $data->trx,
                'post_balance'      => getAmount($owner->balance)
            ]);
        }
    }

    public function manualDepositConfirm()
    {
        $track = Session::get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route('owner.deposit');
        }
        if ($data->status != 0) {
            return redirect()->route('owner.deposit');
        }
        if ($data->method_code > 999) {

            $page_title = 'Deposit Confirm';
            $method = $data->gateway_currency();
            return view('owner.manual_payment.manual_confirm', compact('data', 'page_title', 'method'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::where('status', 0)->where('trx', $track)->with('gateway', 'soldPackage', 'soldPackage.package')->first();

        if (!$data) {
            return redirect()->route('owner.deposit');
        }
        if ($data->status != 0) {
            return redirect()->route('owner.deposit');
        }

        $params = json_decode($data->gateway_currency()->gateway_parameter);

        $rules = [];
        $inputField = [];
        $verifyImages = [];

        if ($params != null) {
            foreach ($params as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');

                    array_push($verifyImages, $key);
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }


        $this->validate($request, $rules);


        $directory = date("Y")."-".date("m")."-".date("d");
        $path = imagePath()['deposit']['path'].'/'.$directory;


        $collection = collect($request);

        $reqField = [];
        if ($params != null) {
            foreach ($collection as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory.'/'.uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $inKey];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $data->detail = $reqField;
        } else {
            $data->detail = null;
        }

        $data->status = 2; // pending
        $data->update();

        $gnl = GeneralSetting::first();


        notify($data->owner, 'DEPOSIT_REQUEST', [
            'method_name'     => $data->gateway_currency()->name,
            'package'         => $data->soldPackage->package->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => getAmount($data->final_amo),
            'amount'          => getAmount($data->amount),
            'charge'          => getAmount($data->charge),
            'currency'        => $gnl->cur_text,
            'rate'            => getAmount($data->rate),
            'trx'             => $data->trx
        ]);

        $notify[] = ['success', 'You have deposit request has been taken.'];
        return redirect()->route('owner.deposit.history')->withNotify($notify);
    }


}
