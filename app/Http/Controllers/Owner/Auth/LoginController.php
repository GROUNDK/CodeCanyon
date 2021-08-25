<?php

namespace App\Http\Controllers\Owner\Auth;

use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\OwnerLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

     /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'owner';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('owner.guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $page_title = "Owner Login";

        return view('owner.auth.login', compact('page_title'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('owner');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $lv = getLatestVersion();

        $gnl = GeneralSetting::first();
        if (systemDetails()['version'] < @json_decode($lv)->version) {
            $gnl->sys_version = $lv;
        } else {
            $gnl->sys_version = null;
        }
        $gnl->save();

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
        $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);

    }

    public function logout(Request $request)
    {
        return $this->logoutGet();
    }


    public function logoutGet()
    {
        $this->guard()->logout();

        request()->session()->invalidate();

        $notify[] = ['success', 'You have been logged out.'];
        return redirect()->route('owner.login')->withNotify($notify);
    }

     /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $owner)
    {
        if ($owner->status == 0) {
            $this->guard()->logout();
            return redirect()->route('owner.login')->withErrors(['Your account has been deactivated.']);
        }


        $owner      = auth()->guard('owner')->user();
        $owner->save();


        $info = json_decode(json_encode(getIpInfo()), true);
        $ownerLogin             = new OwnerLogin();
        $ownerLogin->owner_id   = $owner->id;
        $ownerLogin->owner_ip   =  request()->ip();
        $ownerLogin->longitude  =  @implode(',',$info['long']);
        $ownerLogin->latitude   =  @implode(',',$info['lat']);
        $ownerLogin->location   =  @implode(',',$info['city']) . (" - ". @implode(',',$info['area']) ."- ") . @implode(',',$info['country']) . (" - ". @implode(',',$info['code']) . " ");
        $ownerLogin->country_code = @implode(',',$info['code']);
        $ownerLogin->browser = @$info['browser'];
        $ownerLogin->os = @$info['os_platform'];
        $ownerLogin->country =  @implode(',', $info['country']);
        $ownerLogin->save();

        return redirect()->route('owner.dashboard');
    }

}
