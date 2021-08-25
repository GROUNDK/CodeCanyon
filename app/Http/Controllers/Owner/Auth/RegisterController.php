<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Owner;
use App\OwnerLogin;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/owner/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('owner.guest');
        $this->middleware('regStatus')->except('registrationNotAllowed');
    }

    public function showRegistrationForm()
    {
        $page_title = "Sign Up";
        return view('owner.auth.register', compact('page_title'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validate = Validator::make($data, [
            'owner_name'    => 'required|string|max:50',
            'owner_name'    => 'required|string|max:50',
            'username'      => 'required|alpha_num|unique:owners|min:6',
            'email'         => 'required|string|email|max:90|unique:owners',
            'mobile'        => 'required|string|max:50|unique:owners',
            'password'      => 'required|string|min:6|confirmed',
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($owner = $this->create($request->all())));

        $this->guard()->login($owner);


        $notify[]= ['sucess', 'Registered Successfully'];


        return $this->registered($request, $owner)
            ?: redirect($this->redirectPath())->withNotify($notify);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $gnl = GeneralSetting::first();
        $owner = new Owner();
        $owner->owner_name = isset($data['owner_name']) ? $data['owner_name'] : null;
        $owner->owner_name = isset($data['owner_name']) ? $data['owner_name'] : null;
        $owner->email = strtolower(trim($data['email']));
        $owner->password = Hash::make($data['password']);
        $owner->username = trim($data['username']);
        $owner->mobile = $data['mobile'];
        $owner->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => ''
        ];
        $owner->status = 1;
        $owner->ev = $gnl->ev ? 0 : 1;
        $owner->sv = $gnl->sv ? 0 : 1;
        $owner->save();

        $info = json_decode(json_encode(getIpInfo()), true);
        $ownerLogin = new OwnerLogin();
        $ownerLogin->owner_id = $owner->id;
        $ownerLogin->owner_ip = request()->ip();
        $ownerLogin->longitude = @implode(',', $info['long']);
        $ownerLogin->latitude = @implode(',', $info['lat']);
        $ownerLogin->location = @implode(',', $info['city']) . (" - " . @implode(',', $info['area']) . "- ") . @implode(',', $info['country']) . (" - " . @implode(',', $info['code']) . " ");
        $ownerLogin->country_code = @implode(',', $info['code']);
        $ownerLogin->browser = @$info['browser'];
        $ownerLogin->os = @$info['os_platform'];
        $ownerLogin->country = @implode(',', $info['country']);
        $ownerLogin->save();
        return $owner;
    }

    public function registered()
    {
        return redirect()->route('owner.dashboard');
    }

    public function guard(){
        return Auth::guard('owner');
    }

}
