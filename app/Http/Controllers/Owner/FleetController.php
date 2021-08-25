<?php

namespace App\Http\Controllers\Owner;

use App\FleetType;
use App\Http\Controllers\Controller;
use App\SeatLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FleetController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->owner = Auth::guard('owner')->user();
            return $next($request);
        });
    }

    public function seatLayout()
    {
        $page_title     = 'Seat Layouts';
        $seat_layouts   = $this->owner->seatLayouts;
        $empty_message  = 'No Seat Layout Yet';

        return view('owner.fleet_type.seat_layout.index', compact('page_title', 'empty_message', 'seat_layouts'));
    }

    public function seatLayoutAdd(Request $request, $id)
    {
        $request->validate([
            'layout' => 'required|string'
        ]);

        if($id == 0){
            $create = $this->owner->seatLayouts()->create([
                'layout' => $request->layout
            ]);

            if($create)
            $notify[] = ['success', 'Seat Layout Added Successfully'];
        }else{
            $update = $this->owner->seatLayouts()->where('id', $id)->first()->update([
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
        if($seat_layout->owner_id != $this->owner->id)
        abort(401);
        $seat_layout->delete();
        $notify[] = ['success', 'Seat Layout Deleted Successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function fleetType()
    {
        $page_title     = 'Fleet Types';
        $fleet_types    = $this->owner->fleetTypes()->get();
        $seat_layouts   = $this->owner->seatLayouts;
        $empty_message  = 'No Fleet Type Yet';
        return view('owner.fleet_type.index', compact('page_title', 'empty_message', 'fleet_types', 'seat_layouts'));
    }

    public function fleetTypeCreate()
    {
        $page_title     = 'Create Fleet Type';
        $seat_layouts   = $this->owner->seatLayouts;

        return view('owner.fleet_type.create', compact('page_title', 'seat_layouts'));
    }

    public function fleetTypeEdit($id)
    {

        $fleet_type     = FleetType::findOrFail($id);
        if($fleet_type->owner_id != $this->owner->id)
        abort(401);
        $seat_layouts   = $this->owner->seatLayouts;
        $page_title     = 'Edit Fleet Type';

        return view('owner.fleet_type.create', compact('page_title', 'seat_layouts', 'fleet_type'));
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
            $create = $this->owner->fleetTypes()->create([
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

            $fleet_type = $this->owner->fleetTypes()->where('id', $id)->with('buses', 'trips')->first();

            if($fleet_type->trips->count() > 0){
                $fleet_type->trips()->update([
                    'status'        => $request->status?1:0,
                ]);
            }

            if($fleet_type->buses->count() > 0){
                $fleet_type->buses()->update([
                    'status'        => $request->status?1:0,
                ]);
            }

            $update = $fleet_type->update([
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
        $buses         = $this->owner->buses()->with('fleetType')->get();
        $fleet_types   = $this->owner->fleetTypes()->whereStatus('1')->get();
        $empty_message = "No bus added yet";
        return view('owner.fleet_type.bus.index', compact('page_title', 'buses', 'fleet_types', 'empty_message'));
    }

    public function busCreate()
    {
        $page_title    = 'Add New Bus';
        $fleet_types   = $this->owner->fleetTypes()->whereStatus('1')->get();
        return view('owner.fleet_type.bus.create', compact('page_title', 'fleet_types'));
    }

    public function busEdit($id)
    {
        $bus     = $this->owner->buses()->where('id', $id)->firstOrFail();
        $page_title    = 'Update Bus';
        $fleet_types   = $this->owner->fleetTypes()->whereStatus('1')->get();

        return view('owner.fleet_type.bus.create', compact('page_title', 'fleet_types', 'bus'));
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
            $create = $this->owner->buses()->create([
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
            $update = $this->owner->buses()->where('id', $id)->first()->update([
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

}
