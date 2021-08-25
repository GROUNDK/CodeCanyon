<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Driver;
use Facade\Ignition\Support\Packagist\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->owner = Auth::guard('owner')->user();
            return $next($request);
        });
    }

    public function index()
    {
        $page_title     = 'All Drivers';
        $empty_message  = 'No Driver Yet';
        $drivers        = Driver::where('owner_id', $this->owner->id)->orderByDesc('created_at')->get();
        return view('owner.driver.index', compact('page_title', 'empty_message', 'drivers'));
    }

    public function trashed()
    {
        $page_title     = 'Trashed Drivers';
        $empty_message  = 'No Trashed Driver Found';
        $drivers        = Driver::onlyTrashed()->where('owner_id', $this->owner->id)->orderByDesc('created_at')->get();
        return view('owner.driver.index', compact('page_title', 'empty_message', 'drivers'));
    }

    public function create()
    {
        $page_title = 'Add New Driver';
        return view('owner.driver.create', compact('page_title'));
    }

    public function edit(Driver $driver)
    {
        if($driver->owner_id != $this->owner->id)
        abort(401);
        $page_title = 'Update Driver';

        return view('owner.driver.create', compact('page_title', 'driver'));
    }

    public function store(Request $request, $id)
    {
        $validation_rule = [
            'name'     => 'required|string|max:50',
            'address' => 'nullable|string|max:400',
            'password' => 'sometimes|required|string|min:6|confirmed',
        ];

        if($id==0){
            $validation_rule = array_merge($validation_rule, [
                'username'  => 'required|alpha_dash|unique:drivers|min:6|max:50',
                'email'     => 'required|string|email|max:90|unique:drivers',
                'mobile'    => 'nullable|string|max:50|unique:drivers',
            ]);
            $driver = new Driver();
            $driver->password = Hash::make($request->password);
            $notify[] = ['success', 'New Driver Added Successfully'];
        }else{
            $validation_rule = array_merge($validation_rule, [
                'username' => 'required|alpha_num|min:6|max:50|unique:drivers,username,'.$id,
                'email' => 'required|string|email|max:90|unique:drivers,email,'.$id,
                'mobile' => 'nullable|string|max:50|unique:drivers,mobile,'.$id,
            ]);

            $notify[] = ['success', 'Driver Updated Successfully'];
            $driver = Driver::find($id);
        }

        $request->validate($validation_rule);

        $address = [
            'address'       => $request->address??null,
            'state'         => $driver->state??null,
            'zip'           => $driver->zip??null,
            'country'       => $driver->country??null,
            'city'          => $driver->city??null
        ];

        $driver->owner_id      = $this->owner->id;
        $driver->name          = $request->name;
        $driver->username      = $request->username;
        $driver->email         = $request->email;
        $driver->mobile        = $request->mobile??null;
        $driver->address       = $address;
        $driver->status        = $request->status?true:false;
        $driver->save();

        if($driver){
            return redirect()->back()->withNotify($notify);
        }else{
            abort(404);
        }
    }

    public function destroy($id)
    {
        $driver = Driver::where('id', $id)->withTrashed()->with('assignedBuses')->first();


        if($driver->assignedBuses->count() > 0) {
            $notify[] = ['error', 'Sorry You Can\'t Delete this Driver. Because it Has Some Dependencies'];
            return redirect()->back()->withNotify($notify);
        }

        if ($driver->trashed()){
            $driver->restore();
            $notify[] = ['success', 'Driver Restored Successfully'];
            return redirect()->back()->withNotify($notify);
        }else{
            $driver->delete();
            $notify[] = ['success', 'Driver Deleted Successfully'];
            return redirect()->back()->withNotify($notify);
        }
    }
}
