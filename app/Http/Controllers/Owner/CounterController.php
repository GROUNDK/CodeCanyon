<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\CounterManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CounterController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->owner = Auth::guard('owner')->user();
            return $next($request);
        });
    }

    public function index()
    {
        $page_title         = 'All Counters';
        $empty_message      = 'No Counter Have Been Ceated Yet';
        $counter_managers   = $this->owner->counterManagers()->with('counter')->doesntHave('counter')->where('status', 1)->orderBy('name')->get();
        $counters           = $this->owner->counters()->with('counterManager')->orderByDesc('created_at')->get();
        return view('owner.counter.index', compact('page_title', 'empty_message', 'counters', 'counter_managers'));
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
            $create = $this->owner->counters()->create([
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
            $update = $this->owner->counters()->where('id', $id)->first()->update([
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
        $counter_managers   = $this->owner->counterManagers()->orderByDesc('created_at')->get();
        return view('owner.counter_manager.index', compact('page_title', 'empty_message', 'counter_managers'));
    }

    public function counterManagerCreate()
    {
        $page_title = 'Add New Counter Manager';
        return view('owner.counter_manager.create', compact('page_title'));
    }

    public function counterManagerEdit(CounterManager $counter_manager)
    {
        if($counter_manager->owner_id != auth()->guard('owner')->user()->id)
        abort(401);
        $page_title = 'Update Counter Manager';

        return view('owner.counter_manager.create', compact('page_title', 'counter_manager'));
    }

    public function counterManagerStore(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:50',
            'address'   => 'nullable|string|max:400',
            'password'  => 'sometimes|required|string|min:6|confirmed',
            'username'  => 'required|alpha_dash|min:6|max:50|unique:counter_managers,username,'.$id,
            'email'     => 'required|string|email|max:90|unique:counter_managers,email,'.$id,
            'mobile'    => 'nullable|string|max:50|unique:counter_managers,mobile,'.$id,
        ]);


        if($id == 0){

            $address = [
                'address'       => $request->address
            ];

            $create = $this->owner->counterManagers()->create([
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

            $counterManager = $this->owner->counterManagers()->where('id', $id)->first();
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
        $counter_manager = $this->owner->counterManagers()->where('id', $id)->with('counter', 'tickets')->first();
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
