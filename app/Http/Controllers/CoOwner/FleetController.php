<?php

namespace App\Http\Controllers\CoOwner;

use App\FleetType;
use App\Http\Controllers\Controller;
use App\SeatLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FleetController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->co_owner = Auth::guard('co-owner')->user();
            return $next($request);
        });
    }

    public function seatLayout()
    {
        $page_title     = 'Seat Layouts';
        $seat_layouts   = $this->co_owner->owner->seatLayouts;
        $empty_message  = 'No Seat Layout Yet';
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.fleet_type.seat_layout.index', compact('page_title', 'empty_message', 'seat_layouts', 'owner', 'co_owner'));
    }

    public function seatLayoutAdd(Request $request, $id)
    {
        $request->validate([
            'layout' => 'required|string'
        ]);

        if($id == 0){
            $create = $this->co_owner->owner->seatLayouts()->create([
                'layout' => $request->layout
            ]);

            if($create)
            $notify[] = ['success', 'Seat Layout Added Successfully'];
        }else{
            $update = $this->co_owner->owner->seatLayouts()->where('id', $id)->first()->update([
                'layout' => $request->layout
            ]);
            if($update)
            $notify[] = ['success', 'Seat Layout Updated Successfully'];
        }

        return redirect()->back()->withNotify($notify);
    }

    public function seatLayoutRemove($id)
    {
        $seat_layout = SeatLayout::findOrFail($id);
        if($seat_layout->owner_id != $this->co_owner->owner->id)
        abort(401);
        $seat_layout->delete();
        $notify[] = ['success', 'Seat Layout Deleted Successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function fleetType()
    {
        $page_title     = 'Fleet Types';
        $fleet_types    = $this->co_owner->owner->fleetTypes()->get();
        $seat_layouts   = $this->co_owner->owner->seatLayouts;
        $empty_message  = 'No Fleet Type Yet';
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.fleet_type.index', compact('page_title', 'empty_message', 'fleet_types', 'seat_layouts', 'owner', 'co_owner'));
    }

    public function fleetTypeCreate()
    {
        $page_title     = 'Create Fleet Type';
        $seat_layouts   = $this->co_owner->owner->seatLayouts;
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.fleet_type.create', compact('page_title', 'seat_layouts', 'owner', 'co_owner'));
    }

    public function fleetTypeEdit($id)
    {

        $fleet_type     = FleetType::findOrFail($id);
        if($fleet_type->owner_id != $this->co_owner->owner->id)
        abort(401);
        $seat_layouts   = $this->co_owner->owner->seatLayouts;
        $page_title     = 'Edit Fleet Type';
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.fleet_type.create', compact('page_title', 'seat_layouts', 'fleet_type', 'owner', 'co_owner'));
    }

    public function fleetTypeStore(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string',
            'number_of_deck'=> 'required|numeric',
            'seats'         => 'required|array|min:1',
            'seats.*'       => 'required|numeric|gt:0',
            'seat_layout'   => 'required|string',
            'has_ac'        => 'required|numeric|between:0,1',
        ],[
            'seats.*.required'  => 'Seat number for all deck is required',
            'seats.*.numeric'   => 'Seat number for all deck is must be a number',
            'seats.*.gt:0'      => 'Seat number for all deck is must be greater than 0',
        ]);

        if($id == 0){
            $create = $this->co_owner->owner->fleetTypes()->create([
            'name'          => $request->name,
                'deck'          => $request->number_of_deck,
                'seat_layout'   => $request->seat_layout,
                'seats'         => $request->seats,
                'has_ac'        => $request->has_ac,
                'status'        => $request->status?1:0,
            ]);

            if($create)
            $notify[] = ['success', 'Fleet Type Added Successfully'];

        }else{
            $update = $this->co_owner->owner->fleetTypes()->where('id', $id)->first()->update([
                'name'          => $request->name,
                'deck'          => $request->number_of_deck,
                'seat_layout'   => $request->seat_layout,
                'seats'         => $request->seats,
                'has_ac'        => $request->has_ac,
                'status'        => $request->status?1:0,
            ]);

            if($update)
            $notify[] = ['success', 'Fleet Type Updated Successfully'];
        }

        return redirect()->back()->withNotify($notify);
    }

    public function bus()
    {
        $page_title    = 'All Buses';
        $buses         = $this->co_owner->owner->buses()->with('fleetType')->get();
        $fleet_types   = $this->co_owner->owner->fleetTypes;
        $empty_message = "No bus added yet";
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.fleet_type.bus.index', compact('page_title', 'buses', 'fleet_types', 'empty_message', 'owner', 'co_owner'));
    }

    public function busTrashed()
    {
        $page_title    = 'All Buses';
        $buses         = $this->co_owner->owner->buses()->onlyTrashed()->with('fleetType')->get();
        $fleet_types   = $this->co_owner->owner->fleetTypes;
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        $empty_message = "No bus added yet";
        return view('co-owner.fleet_type.bus.index', compact('page_title', 'buses', 'fleet_types', 'empty_message', 'owner', 'co_owner'));
    }

    public function busCreate()
    {
        $page_title    = 'Add New Bus';
        $fleet_types   = $this->co_owner->owner->fleetTypes;
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.fleet_type.bus.create', compact('page_title', 'fleet_types', 'owner', 'co_owner'));
    }

    public function busEdit($id)
    {
        $bus     = $this->co_owner->owner->buses()->where('id', $id)->firstOrFail();
        $page_title    = 'Update Bus';
        $fleet_types   = $this->co_owner->owner->fleetTypes;
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.fleet_type.bus.create', compact('page_title', 'fleet_types', 'bus', 'owner', 'co_owner'));
    }

    public function busStore (Request $request, $id)
    {
        $request->validate([
            'fleet_type'        => 'required|numeric',
            'nick_name'         => 'required|string',
            'registration_no'   => 'required|string|unique:buses,registration_no,'.$id,
            'engine_no'         => 'required|string|unique:buses,engine_no,'.$id,
            'model_no'          => 'required|string',
            'chasis_no'         => 'required|string|unique:buses,chasis_no,'.$id,
            'owner_name'        => 'required|string',
            'owner_phone'       => 'required|string',
        ]);


        if($id == 0){
            $create = $this->co_owner->owner->buses()->create([
                'fleet_type_id'     => $request->fleet_type,
                'nick_name'         => $request->nick_name,
                'registration_no'   => $request->registration_no,
                'engine_no'         => $request->engine_no,
                'model_no'          => $request->model_no,
                'chasis_no'         => $request->chasis_no,
                'owner'             => $request->owner_name,
                'owner_phone'       => $request->owner_phone,
                'brand_name'        => $request->brand_name,
                'status'            => $request->status?1:0,
            ]);
            if($create)
            $notify[] = ['success', 'New Bus Added Successfully'];

        }else{
            $update = $this->co_owner->owner->buses()->where('id', $id)->first()->update([
                'fleet_type_id'        => $request->fleet_type,
                'nick_name'         => $request->nick_name,
                'registration_no'   => $request->registration_no,
                'engine_no'         => $request->engine_no,
                'model_no'          => $request->model_no,
                'chasis_no'         => $request->chasis_no,
                'owner'             => $request->owner_name,
                'owner_phone'       => $request->owner_phone,
                'brand_name'        => $request->brand_name,
                'status'            => $request->status?1:0,
            ]);

            if($update)
            $notify[] = ['success', 'Bus Updated Successfully'];
        }
        return redirect()->back()->withNotify($notify);
    }

    public function busRemove($id)
    {
        $bus = $this->co_owner->owner->buses()->where('id', $id)->withTrashed()->first();
        if ($bus->trashed()){
            $bus->restore();
            $notify[] = ['success', 'Bus Restored Successfully'];
            return redirect()->back()->withNotify($notify);
        }else{
            $bus->delete();
            $notify[] = ['success', 'Bus Deleted Successfully'];
            return redirect()->back()->withNotify($notify);
        }
    }

}
