<?php
namespace App\Http\Controllers\Admin;

use App\Deposit;
use App\GeneralSetting;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Owner;
use App\OwnerLogin;
use Carbon\Carbon;

class ManageUsersController extends Controller
{
    public function allUsers()
    {
        $page_title = 'Manage Owners';
        $empty_message = 'No owner found';
        $owners = Owner::latest()->paginate(getPaginate());
        return view('admin.owners.list', compact('page_title', 'empty_message', 'owners'));
    }

    public function activeUsers()
    {
        $page_title = 'Manage Active Owners';
        $empty_message = 'No active owner found';
        $owners = Owner::active()->latest()->paginate(getPaginate());
        return view('admin.owners.list', compact('page_title', 'empty_message', 'owners'));
    }

    public function bannedUsers()
    {
        $page_title = 'Banned Owners';
        $empty_message = 'No banned owner found';
        $owners = Owner::banned()->latest()->paginate(getPaginate());
        return view('admin.owners.list', compact('page_title', 'empty_message', 'owners'));
    }

    public function emailUnverifiedUsers()
    {
        $page_title = 'Email Unverified Owners';
        $empty_message = 'No email unverified owner found';
        $owners = Owner::emailUnverified()->latest()->paginate(getPaginate());
        return view('admin.owners.list', compact('page_title', 'empty_message', 'owners'));
    }
    public function emailVerifiedUsers()
    {
        $page_title = 'Email Verified Owners';
        $empty_message = 'No email verified owner found';
        $owners = Owner::emailVerified()->latest()->paginate(getPaginate());
        return view('admin.owners.list', compact('page_title', 'empty_message', 'owners'));
    }


    public function smsUnverifiedUsers()
    {
        $page_title = 'SMS Unverified Owners';
        $empty_message = 'No sms unverified owner found';
        $owners = Owner::smsUnverified()->latest()->paginate(getPaginate());
        return view('admin.owners.list', compact('page_title', 'empty_message', 'owners'));
    }
    public function smsVerifiedUsers()
    {
        $page_title = 'SMS Verified Owners';
        $empty_message = 'No sms verified owner found';
        $owners = Owner::smsVerified()->latest()->paginate(getPaginate());
        return view('admin.owners.list', compact('page_title', 'empty_message', 'owners'));
    }



    public function search(Request $request, $scope)
    {
        $search = $request->search;

        $owners = Owner::where(function ($owner) use ($search) {
            $owner->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%")
                ->orWhere('owner_name', 'like', "%$search%");
        });
        $page_title = '';
        switch ($scope) {
            case 'active':
                $page_title .= 'Active ';
                $owners = $owners->where('status', 1);
                break;
            case 'banned':
                $page_title .= 'Banned';
                $owners = $owners->where('status', 0);
                break;
            case 'emailUnverified':
                $page_title .= 'Email Unerified ';
                $owners = $owners->where('ev', 0);
                break;
            case 'smsUnverified':
                $page_title .= 'SMS Unverified ';
                $owners = $owners->where('sv', 0);
                break;
        }
        $owners = $owners->paginate(getPaginate());
        $page_title .= 'User Search - ' . $search;
        $empty_message = 'No search result found';
        return view('admin.owners.list', compact('page_title', 'search', 'scope', 'empty_message', 'owners'));
    }


    public function detail($id)
    {
        $page_title         = 'Owner Detail';
        $owner              = Owner::findOrFail($id);
        $bought_packages    = $owner->boughtPackages()->count();
        $totalDeposit       = Deposit::where('owner_id',$owner->id)->where('status',1)->sum('amount');
        $totalTransaction   = Transaction::where('owner_id',$owner->id)->count();
        return view('admin.owners.detail', compact('page_title', 'owner','totalDeposit','totalTransaction', 'bought_packages'));
    }


    public function update(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);
        $request->validate([
            'name'      => 'required|max:50',
        ]);

        if ($request->email != $owner->email && Owner::whereEmail($request->email)->whereId('!=', $owner->id)->count() > 0) {
            $notify[] = ['error', 'Email already exists.'];
            return back()->withNotify($notify);
        }
        if ($request->mobile != $owner->mobile && Owner::where('mobile', $request->mobile)->whereId('!=', $owner->id)->count() > 0) {
            $notify[] = ['error', 'Phone number already exists.'];
            return back()->withNotify($notify);
        }

        $owner->update([
            'owner_name'=> $request->name,
            'address'   => [
                                'address'   => $request->address,
                                'city'      => $request->city,
                                'state'     => $request->state,
                                'zip'       => $request->zip,
                                'country'   => $request->country,
                            ],
            'status' => $request->status ? 1 : 0,
            'ev' => $request->ev ? 1 : 0,
            'sv' => $request->sv ? 1 : 0,
        ]);

        $notify[] = ['success', 'Owner detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    public function userLoginHistory($id)
    {
        $owner = Owner::findOrFail($id);
        $page_title = 'Owner Login History - ' . $owner->username;
        $empty_message = 'No owners login found.';
        $login_logs = $owner->login_logs()->latest()->paginate(getPaginate());
        return view('admin.owners.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function loginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $page_title = 'Owner Login History Search - ' . $search;
            $empty_message = 'No search result found.';
            $login_logs = OwnerLogin::whereHas('owner', function ($query) use ($search) {
                $query->where('username', $search);
            })->latest()->paginate(getPaginate());
            return view('admin.owners.logins', compact('page_title', 'empty_message', 'search', 'login_logs'));
        }
        $page_title = 'Owner Login History';
        $empty_message = 'No owners login found.';
        $login_logs = OwnerLogin::latest()->paginate(getPaginate());
        return view('admin.owners.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function loginIpHistory($ip)
    {
        $page_title = 'Login By - ' . $ip;
        $login_logs = OwnerLogin::where('owner_ip',$ip)->latest()->paginate(getPaginate());
        $empty_message = 'No owners login found.';
        return view('admin.owners.logins', compact('page_title', 'empty_message', 'login_logs'));

    }



    public function showEmailSingleForm($id)
    {
        $owner = Owner::findOrFail($id);
        $page_title = 'Send Email To: ' . $owner->username;
        return view('admin.owners.email_single', compact('page_title', 'owner'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $owner = Owner::findOrFail($id);
        send_general_email($owner->email, $request->subject, $request->message, $owner->username);
        $notify[] = ['success', $owner->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function transactions(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $page_title = 'Search Owner Transactions : ' . $owner->username;
            $transactions = $owner->transactions()->where('trx', $search)->with('owner')->latest()->paginate(getPaginate());
            $empty_message = 'No transactions';
            return view('admin.reports.transactions', compact('page_title', 'search', 'owner', 'transactions', 'empty_message'));
        }
        $page_title = 'Owner Transactions : ' . $owner->username;
        $transactions = $owner->transactions()->with('owner')->latest()->paginate(getPaginate());
        $empty_message = 'No transactions';
        return view('admin.reports.transactions', compact('page_title', 'owner', 'transactions', 'empty_message'));
    }

    public function deposits(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $page_title = 'Search Owner Deposits : ' . $owner->username;
            $deposits = $owner->deposits()->where('trx', $search)->latest()->paginate(getPaginate());
            $empty_message = 'No deposits';
            return view('admin.deposit.log', compact('page_title', 'search', 'owner', 'deposits', 'empty_message'));
        }

        $page_title = 'Owner Deposit : ' . $owner->username;
        $deposits = $owner->deposits()->latest()->paginate(getPaginate());
        $empty_message = 'No deposits';
        return view('admin.deposit.log', compact('page_title', 'owner', 'deposits', 'empty_message'));
    }


    public function showEmailAllForm()
    {
        $page_title = 'Send Email To All Owners';
        return view('admin.owners.email_all', compact('page_title'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (Owner::where('status', 1)->cursor() as $owner) {
            send_general_email($owner->email, $request->subject, $request->message, $owner->username);
        }

        $notify[] = ['success', 'All owners will receive an email shortly.'];
        return back()->withNotify($notify);
    }


}
