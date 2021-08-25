<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->driver = Auth::guard('driver')->user();
            return $next($request);
        });
    }

    public function dashboard()
    {
        return self::trips();
    }

    public function trips()
    {
        $data['trips']      = $this->driver->assignedBuses()->with('trip', 'supervisor', 'bus', 'bus.fleetType')->where('status', 1)->paginate(getPaginate());
        $data['owner']      = $this->driver->owner;
        $data['page_title'] = 'Assigned Trips';
        $data['empty_message'] = 'No Trip Found';
        return view('driver.trips', $data);
    }

    public function viewTrips($id)
    {
        $trip               = $this->driver->assignedBuses()
                            ->where('id', $id)->first()->trip()->with(['route' ,'fleetType', 'schedule', 'bookedTickets' => function($q){
                                return $q->where('date_of_journey', Carbon::now()->format('Y-m-d'));
                            }])->first();

        $page_title         = $trip->title;
        $stoppages          = $trip->route->stoppages;

        return view('driver.trip_view', compact('page_title', 'trip', 'stoppages'));
    }

    public function profile()
    {
        $page_title = 'Profile';
        $driver = $this->driver;
        return view('driver.profile', compact('page_title', 'driver'));
    }

    public function profileUpdate(Request $request)
    {
        $driver = $this->driver;

        $this->validate($request, [
            'name'          => 'required|string|max:50',
            'username'      => 'required|alpha_num|min:5|unique:owners,username,'.$driver->id,
            'email'         => 'required|string|email|max:90|unique:owners,email,'.$driver->id,
            'mobile'        => 'required|string|max:50|unique:owners,mobile,'.$driver->id,
            'image'         => 'nullable|image|mimes:jpg,jpeg,png',
            'address'       => "sometimes|required|max:80",
            'state'         => 'sometimes|required|max:80',
            'zip'           => 'sometimes|required|max:40',
            'city'          => 'sometimes|required|max:50',
            'country'       => 'sometimes|required|string|max:50',
        ]);

        $address = [
            'address'       => $request->address,
            'state'         => $request->state,
            'zip'           => $request->zip,
            'country'       => $request->country,
            'city'          => $request->city
        ];

        if ($request->hasFile('image')) {
            try {
                $old = $driver->image ?: null;
                $driver->image = uploadImage($request->image, 'assets/owner/images/driver/', '400X400', $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $driver->name         = $request->name;
        $driver->username     = $request->username;
        $driver->email        = $request->email;
        $driver->mobile       = $request->mobile;
        $driver->address      = $address;

        $driver->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('driver.profile')->withNotify($notify);
    }

    public function password()
    {
        $page_title = 'Password Setting';
        $driver = $this->driver;

        return view('driver.password', compact('page_title', 'driver'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password'  => 'required',
            'password'      => 'required|min:6|confirmed',
        ]);

        $user = $this->driver;
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('driver.password')->withNotify($notify);
    }

}
