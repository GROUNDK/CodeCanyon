<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->owner = Auth::guard('owner')->user();
            return $next($request);
        });
    }

    public function index()
    {
        $page_title = 'All Supervisors';
        $empty_message = 'No Supervisor Yet';
        $supervisors = Supervisor::where('owner_id', $this->owner->id)->orderByDesc('created_at')->get();
        return view('owner.supervisor.index', compact('page_title', 'empty_message', 'supervisors'));
    }


    public function trashed()
    {
        $page_title = 'Trashed Supervisors';
        $empty_message = 'No Supervisor Yet';
        $supervisors = Supervisor::onlyTrashed()->where('owner_id', $this->owner->id)->orderByDesc('created_at')->get();
        return view('owner.supervisor.index', compact('page_title', 'empty_message', 'supervisors'));
    }

    public function create()
    {
        $page_title = 'Add New Supervisor';
        return view('owner.supervisor.create', compact('page_title'));
    }

    public function edit(Supervisor $supervisor)
    {
        if($supervisor->owner_id != $this->owner->id)
        abort(401);
        $page_title = 'Update Supervisor';

        return view('owner.supervisor.create', compact('page_title', 'supervisor'));
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
                'username' => 'required|alpha_dash|unique:supervisors|min:6|max:50',
                'email' => 'required|string|email|max:90|unique:supervisors',
                'mobile' => 'nullable|string|max:50|unique:supervisors',
            ]);
            $supervisor = new Supervisor();
            $supervisor->password = Hash::make($request->password);
            $notify[] = ['success', 'New Supervisor Added Successfully'];
        }else{
            $validation_rule = array_merge($validation_rule, [
                'username' => 'required|alpha_num|min:6|max:50|unique:supervisors,username,'.$id,
                'email' => 'required|string|email|max:90|unique:supervisors,email,'.$id,
                'mobile' => 'nullable|string|max:50|unique:supervisors,mobile,'.$id,
            ]);

            $notify[] = ['success', 'Supervisor Updated Successfully'];
            $supervisor = Supervisor::find($id);
        }

        $request->validate($validation_rule);
        $address = [
            'address'       => $request->address??null,
            'state'         => $supervisor->state??null,
            'zip'           => $supervisor->zip??null,
            'country'       => $supervisor->country??null,
            'city'          => $supervisor->city??null
        ];

        $supervisor->owner_id      = $this->owner->id;
        $supervisor->name          = $request->name;
        $supervisor->username      = $request->username;
        $supervisor->email         = $request->email;
        $supervisor->mobile        = $request->mobile??null;
        $supervisor->address       = $address;
        $supervisor->status        = $request->status?true:false;
        $supervisor->save();

        if($supervisor){
            return redirect()->back()->withNotify($notify);
        }else{
            abort(500);
        }
    }

    public function destroy($id)
    {
        $supervisor = Supervisor::where('id', $id)->withTrashed()->with('assignedBuses')->first();

        if($supervisor->assignedBuses->count() > 0) {
            $notify[] = ['error', 'Sorry You Can\'t Delete this Supervisor. Because it Has Some Dependencies'];
            return redirect()->back()->withNotify($notify);
        }

        if ($supervisor->trashed()){
            $supervisor->restore();
            $notify[] = ['success', 'Supervisor Restored Successfully'];
            return redirect()->back()->withNotify($notify);
        }else{
            $supervisor->delete();
            $notify[] = ['success', 'Supervisor Deleted Successfully'];
            return redirect()->back()->withNotify($notify);
        }
    }
}
