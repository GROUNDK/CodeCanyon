<?php

namespace App\Http\Controllers\Owner;

use App\CoOwner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CoOwnerController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->owner = Auth::guard('owner')->user();
            return $next($request);
        });
    }

    public function index()
    {
        $page_title = 'All Co-Owners';
        $empty_message = 'No CoOwner Yet';
        $co_owners = CoOwner::where('owner_id', $this->owner->id)->orderByDesc('created_at')->get();
        return view('owner.co-owner.index', compact('page_title', 'empty_message', 'co_owners'));
    }

    public function trashed()
    {
        $page_title = 'Trashed Co-Owners';
        $empty_message = 'No Co-Owner Yet';
        $co_owners = CoOwner::onlyTrashed()->where('owner_id', $this->owner->id)->orderByDesc('created_at')->get();
        return view('owner.co-owner.index', compact('page_title', 'empty_message', 'co_owners'));
    }

    public function create()
    {
        $page_title = 'Add New CoOwner';
        return view('owner.co-owner.create', compact('page_title'));
    }

    public function edit(CoOwner $co_admin)
    {
        if($co_admin->owner_id != $this->owner->id)
        abort(401);
        $page_title = 'Update CoOwner';

        return view('owner.co-owner.create', compact('page_title', 'co_admin'));
    }

    public function store(Request $request, $id)
    {
        $validation_rule = [
            'name'      => 'required|string|max:50',
            'address'   => 'nullable|string|max:400',
            'password'  => 'sometimes|required|string|min:6|confirmed',
        ];

        if($id==0){
            $validation_rule = array_merge($validation_rule, [
                'username'  => 'required|alpha_dash|unique:co_owners|min:6|max:50',
                'email'     => 'required|string|email|max:90|unique:co_owners',
                'mobile'    => 'required|string|max:50|unique:co_owners',
            ]);
            $co_admin = new CoOwner();
            $co_admin->password = Hash::make($request->password);
            $notify[] = ['success', 'New CoOwner Added Successfully'];
        }else{
            $validation_rule = array_merge($validation_rule, [
                'username' => 'required|alpha_num|min:6|max:50|unique:co_owners,username,'.$id,
                'email' => 'required|string|email|max:90|unique:co_owners,email,'.$id,
                'mobile' => 'required|string|max:50|unique:co_owners,mobile,'.$id,
            ]);

            $notify[] = ['success', 'CoOwner Updated Successfully'];
            $co_admin = CoOwner::find($id);
        }

        $request->validate($validation_rule);

        $address = [
            'address'       => $request->address??null,
            'state'         => $co_admin->state??null,
            'zip'           => $co_admin->zip??null,
            'country'       => $co_admin->country??null,
            'city'          => $co_admin->city??null
        ];


        $co_admin->owner_id      = $this->owner->id;
        $co_admin->name          = $request->name;
        $co_admin->username      = $request->username;
        $co_admin->email         = $request->email;
        $co_admin->mobile        = $request->mobile??null;
        $co_admin->address       = $address;
        $co_admin->status        = $request->status?true:false;
        $co_admin->save();

        if($co_admin){
            return redirect()->back()->withNotify($notify);
        }else{
            abort(500);
        }
    }

    public function destroy($id)
    {
        $co_admin = CoOwner::where('id', $id)->withTrashed()->first();
        if($co_admin->owner_id != $this->owner->id)
        abort(401);

        if ($co_admin->trashed()){
            $co_admin->restore();
            $notify[] = ['success', 'CoOwner Restored Successfully'];
            return redirect()->back()->withNotify($notify);
        }else{
            $co_admin->delete();
            $notify[] = ['success', 'CoOwner Deleted Successfully'];
            return redirect()->back()->withNotify($notify);
        }
    }

}
