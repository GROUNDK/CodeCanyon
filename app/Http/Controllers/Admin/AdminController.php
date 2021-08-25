<?php

namespace App\Http\Controllers\Admin;

use App\Deposit;
use App\Gateway;
use App\User;
use App\UserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Owner;
use App\OwnerLogin;
use App\Package;
use App\SoldPackage;

class AdminController extends Controller
{

    public function dashboard()
    {

        $page_title                         = 'Dashboard';
        // User Info

        $widget['verified_users']           = Owner::whereStatus(1)->count();

        $widget['banned_users']             = Owner::whereStatus(0)->count();

        $widget['email_verified_users']     = Owner::where('ev', 1)->count();

        $widget['sms_verified_users']       = Owner::where('sv', 1)->count();


        try{
            $widget['active_packages']          = Package::whereStatus(1)->count();



    $widget['sold_packages']            = SoldPackage::where('status', '!=', 0)->count();




        // Monthly Deposit Report Graph
        $report['months']                   = collect([]);
        $report['deposit_month_amount']     = collect([]);


        $depositsMonth = Deposit::whereYear('created_at', '>=', Carbon::now()->subYear())
                        ->selectRaw("SUM( CASE WHEN status = 1 THEN amount END) as depositAmount")
                        ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
                        ->orderBy('created_at')
                        ->groupBy(DB::Raw("MONTH(created_at)"))->get();

        $depositsMonth->map(function ($aaa) use ($report) {
            $report['months']->push($aaa->months);
            $report['deposit_month_amount']->push(getAmount($aaa->depositAmount));
        });





        // user Browsing, Country, Operating Log
        $user_login_data                    = OwnerLogin::whereDate('created_at', '>=', \Carbon\Carbon::now()->subDay(30))->get(['browser', 'os', 'country']);

        $chart['user_browser_counter']      = $user_login_data->groupBy('browser')->map(function ($item, $key) {
                                                return collect($item)->count();
                                            });

        $chart['user_os_counter']           = $user_login_data->groupBy('os')->map(function ($item, $key) {
                                                return collect($item)->count();
                                            });
        $chart['user_country_counter']      = $user_login_data->groupBy('country')->map(function ($item, $key) {
                                                return collect($item)->count();
                                            })->sort()->reverse()->take(5);

        $payment['payment_method']          = Gateway::count();
        $payment['total_deposit_amount']    = Deposit::where('status',1)->sum('amount');
        $payment['total_deposit_charge']    = Deposit::where('status',1)->sum('charge');

        $payment['total_deposit_pending']   = Deposit::where('status',2)->count();
        $latestSales                        = SoldPackage::with('owner')->where('status', 1)->latest()->limit(6)->get();

        $latestUser = Owner::latest()->limit(6)->get();

    } catch (\Throwable $th) {
        echo $th;
       }

        // return view('admin.dashboard', compact('page_title', 'widget', 'report', 'chart','payment','latestUser' ,'latestSales','depositsMonth'));
        return view('admin.layouts.app', compact('page_title', 'widget', 'report', 'chart','payment','latestUser' ,'latestSales','depositsMonth'));
        // return view('admin.test', compact('page_title', 'widget', 'report', 'chart','payment','latestUser' ,'latestSales','depositsMonth'));
    }


    public function profile()
    {
        $page_title = 'Profile';
        $admin      = Auth::guard('admin')->user();
        return view('admin.profile', compact('page_title', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $user = Auth::guard('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = uploadImage($request->image, 'assets/admin/images/profile/', '400X400', $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $page_title = 'Password Setting';
        $admin      = Auth::guard('admin')->user();
        return view('admin.password', compact('page_title', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password'  => 'required',
            'password'      => 'required|min:5|confirmed',
        ]);

        $user = Auth::guard('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('admin.password')->withNotify($notify);
    }


}
