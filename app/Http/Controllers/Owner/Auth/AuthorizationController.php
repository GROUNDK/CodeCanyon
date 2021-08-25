<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use App\Lib\GoogleAuthenticator;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class AuthorizationController extends Controller
{
    public function __construct()
    {
    }
    public function checkValidCode($user, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$user->ver_code_send_at) return false;
        if ($user->ver_code_send_at->addMinutes($add_min) < Carbon::now()) return false;
        if ($user->ver_code !== $code) return false;
        return true;
    }


    public function authorizeForm()
    {

        if (auth()->guard('owner')->check()) {
            $user = auth()->guard('owner')->user();
            if (!$user->status) {
                Auth::guard('owner')->logout();
            }elseif (!$user->ev) {
                if (!$this->checkValidCode($user, $user->ver_code)) {
                    $user->ver_code = verificationCode(6);
                    $user->ver_code_send_at = Carbon::now();
                    $user->save();
                    send_email($user, 'EVER_CODE', [
                        'code' => $user->ver_code
                    ]);
                }
                $page_title = 'Email verification form';
                return view('owner.auth.authorization.email', compact('user', 'page_title'));
            }elseif (!$user->sv) {
                if (!$this->checkValidCode($user, $user->ver_code)) {
                    $user->ver_code = verificationCode(6);
                    $user->ver_code_send_at = Carbon::now();
                    $user->save();
                    send_sms($user, 'SVER_CODE', [
                        'code' => $user->ver_code
                    ]);
                }
                $page_title = 'SMS verification form';
                return view('owner.auth.authorization.sms', compact('user', 'page_title'));
            }else{
                return redirect()->route('owner.dashboard');
            }

        }

        return redirect()->route('owner.login');
    }

    public function sendVerifyCode(Request $request)
    {
        $user = Auth::guard('owner')->user();


        if ($this->checkValidCode($user, $user->ver_code, 2)) {
            $target_time = $user->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $target_time - time();
            throw ValidationException::withMessages(['resend' => 'Please Try after ' . $delay . ' Seconds']);
        }
        if (!$this->checkValidCode($user, $user->ver_code)) {
            $user->ver_code = verificationCode(6);
            $user->ver_code_send_at = Carbon::now();
            $user->save();
        } else {
            $user->ver_code = $user->ver_code;
            $user->ver_code_send_at = Carbon::now();
            $user->save();
        }

        if ($request->type === 'email') {
            send_email($user, 'EVER_CODE',[
                'code' => $user->ver_code
            ]);

            $notify[] = ['success', 'Email verification code sent successfully'];
            return back()->withNotify($notify);
        } elseif ($request->type === 'phone') {
            send_sms($user, 'SVER_CODE', [
                'code' => $user->ver_code
            ]);
            $notify[] = ['success', 'SMS verification code sent successfully'];
            return back()->withNotify($notify);
        } else {
            throw ValidationException::withMessages(['resend' => 'Sending Failed']);
        }
    }

    public function emailVerification(Request $request)
    {
        $rules = [
            'email_verified_code.*' => 'required',
        ];
        $msg = [
            'email_verified_code.*.required' => 'Email verification code is required',
        ];
        $validate = $request->validate($rules, $msg);


        $email_verified_code =  str_replace(',','',implode(',',$request->email_verified_code));

        $user = Auth::guard('owner')->user();

        if ($this->checkValidCode($user, $email_verified_code)) {
            $user->ev = 1;
            $user->ver_code = null;
            $user->ver_code_send_at = null;
            $user->save();
            return redirect()->intended(route('owner.dashboard'));
        }
        throw ValidationException::withMessages(['email_verified_code' => 'Verification code didn\'t match!']);
    }

    public function smsVerification(Request $request)
    {
        $request->validate([
            'sms_verified_code.*' => 'required',
        ], [
            'sms_verified_code.*.required' => 'SMS verification code is required',
        ]);


        $sms_verified_code =  str_replace(',','',implode(',',$request->sms_verified_code));

        $user = Auth::guard('owner')->user();
        if ($this->checkValidCode($user, $sms_verified_code)) {
            $user->sv = 1;
            $user->ver_code = null;
            $user->ver_code_send_at = null;
            $user->save();
            return redirect()->intended(route('owner.dashboard'));
        }
        throw ValidationException::withMessages(['sms_verified_code' => 'Verification code didn\'t match!']);
    }

}
