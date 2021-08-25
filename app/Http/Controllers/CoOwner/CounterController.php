<?php

namespace App\Http\Controllers\CoOwner;

use App\Http\Controllers\Controller;
use App\CounterManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Symfony\Component\String\s;

class CounterController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->coOwner = Auth::guard('co-owner')->user();
            return $next($request);
        });
    }

    public function index()
    {
        $page_title         = 'All Counters';
        $empty_message      = 'No Counter Have Been Ceated Yet';
        $counter_managers   = $this->coOwner->owner->counterManagers()->with('counter')->doesntHave('counter')->where('status', 1)->orderBy('name')->get();
        $counters           = $this->coOwner->owner->counters()->with('counterManager')->orderByDesc('created_at')->get();
        $owner     = $this->coOwner->owner;
        $co_owner  = $this->coOwner;
        return view('co-owner.counter.index', compact('page_title', 'empty_message', 'counters', 'counter_managers', 'owner', 'co_owner'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'name'              => 'required|string|max:50',
            'mobile'            => 'required|string|max:50',
            'city'              => 'required|string|max:255',
            'location'          => 'nullable|string|max:255',
            'counter_manager'   => 'nullable|integer|gt:0',
        ]);

        if($id == 0){
            $create = $this->coOwner->owner->counters()->create([
                'name'              => $request->name,
                'city'             => $request->city,
                'mobile'            => $request->mobile,
                'counter_manager_id'=> $request->counter_manager??null,
                'location'          => $request->location??null,
                'status'            => $request->status?1:0,
            ]);
            if($create)
            $notify[] = ['success', 'New Counter Successfully'];

        }else{
            $update = $this->coOwner->owner->counters()->where('id', $id)->first()->update([
                'name'              => $request->name,
                'city'             => $request->city,
                'mobile'            => $request->mobile??null,
                'counter_manager_id'=> $request->counter_manager??null,
                'location'          => $request->location??null,
                'status'            => $request->status?1:0,
            ]);

            if($update)
            $notify[] = ['success', 'Counter Updated Successfully'];
        }
        return redirect()->back()->withNotify($notify);
    }

    public function counterManager()
    {
        $page_title         = 'All Counter Managers';
        $empty_message      = 'No Counter Manager Yet';
        $counter_managers   = $this->coOwner->owner->counterManagers()->with('counter')->orderByDesc('created_at')->get();

        $owner     = $this->coOwner->owner;
        $co_owner  = $this->coOwner;
        return view('co-owner.counter_manager.index', compact('page_title', 'empty_message', 'counter_managers', 'owner', 'co_owner'));
    }

    public function counterManagerCreate()
    {
        $owner     = $this->coOwner->owner;
        $co_owner  = $this->coOwner;
        $page_title = 'Add New Counter Manager';
        return view('co-owner.counter_manager.create', compact('page_title','owner', 'co_owner'));
    }

    public function counterManagerEdit(CounterManager $counter_manager)
    {
        if($counter_manager->owner_id != auth()->guard('co-owner')->user()->id)
        abort(401);
        $page_title = 'Update Counter Manager';
        $owner     = $this->coOwner->owner;
        $co_owner  = $this->coOwner;
        return view('co-owner.counter_manager.create', compact('page_title', 'counter_manager','owner', 'co_owner'));
    }

    public function counterManagerStore(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:50',
            'address'   => 'nullable|string|max:400',
            'password'  => 'sometimes|required|string|min:6|confirmed',
            'username'  => 'required|alpha_num|min:6|max:50|unique:counter_managers,username,'.$id,
            'email'     => 'required|string|email|max:90|unique:counter_managers,email,'.$id,
            'mobile'    => 'nullable|string|max:50|unique:counter_managers,mobile,'.$id,
        ]);

        if($id == 0){

            $address = [
                'address'       => $request->address??null
            ];

            $create = $this->coOwner->owner->counterManagers()->create([
                'name'          => $request->name,
                'username'      => $request->username,
                'password'      => Hash::make($request->password),
                'email'         => $request->email,
                'mobile'        => $request->mobile??null,
                'address'       => $address,
                'status'        => $request->status?true:false
            ]);
            if($create)
            $notify[] = ['success', 'New Counter Manager Added Successfully'];

        }else{

            $counterManager = $this->coOwner->owner->counterManagers()->where('id', $id)->first();
            $address = [
                'address'       => $request->address??null,
                'state'         => $counterManager->state??null,
                'zip'           => $counterManager->zip??null,
                'country'       => $counterManager->country??null,
                'city'          => $counterManager->city??null
            ];

            $update = $counterManager->update([
                'name'          => $request->name,
                'username'      => $request->username,
                'email'         => $request->email,
                'mobile'        => $request->mobile??null,
                'address'       => $address,
                'status'        => $request->status?true:false
            ]);

            if($update)
            $notify[] = ['success', 'Counter Manager Updated Successfully'];
        }

        return redirect()->back()->withNotify($notify);
    }

    public function counterManagerDestroy($id)
    {
        $counter_manager = $this->coOwner->owner->counterManagers()->where('id', $id)->with('counter', 'tickets')->first();
        if ($counter_manager->counter == null && $counter_manager->tickets->count()==0){

            $counter_manager->delete();
            $notify[] = ['success', 'Counter Manager Deleted Successfully'];
            return redirect()->back()->withNotify($notify);
        }else{
            $notify[] = ['error', 'Sorry You can\'t Delete this Counter Manager. Because it Has Some Dependencies'];
            return redirect()->back()->withNotify($notify);
        }
    }
}
