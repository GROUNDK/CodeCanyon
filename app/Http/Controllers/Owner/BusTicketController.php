<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\TicketPrice;
use App\TicketPriceByStoppage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BusTicketController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->owner = Auth::guard('owner')->user();
            return $next($request);
        });
    }

    public function prices()
    {
        if(!isset($this->owner->general_settings->currency_symbol) && (!isset($this->owner->general_settings->currency_symbol))){
            $notify[]=['error','Please Set Your Currency Settings From General Setting'];
            return back()->withNotify($notify);
        }

        $page_title     = 'All Ticket Price List';
        $empty_message  = 'No Ticket Price Found';
        $ticket_prices  = $this->owner->ticketPrices()
                        ->with('route', 'fleetType')
                        ->whereHas('fleetType')
                        ->whereHas('route')
                        ->groupBy('route_id')
                        ->groupBy('fleet_type_id')
                        ->paginate(getPaginate());

        $fleet_types= $this->owner->fleetTypes()->where('status', 1)->get();
        $routes     = $this->owner->routes()->where('status', 1)->get();
        $owner      = $this->owner;
        return view('owner.trip.ticket.price.index', compact('page_title', 'empty_message', 'ticket_prices', 'fleet_types', 'routes', 'owner'));
    }

    public function create()
    {
        $page_title = 'Set Ticket Price';
        $fleet_types= $this->owner->fleetTypes()->where('status', 1)->get();
        $routes     = $this->owner->routes()->where('status', 1)->get();
        $owner          = $this->owner;
        return view('owner.trip.ticket.price.create', compact('page_title', 'fleet_types', 'routes', 'owner'));
    }

    public function edit($id)
    {
        $ticket_prices  = $this->owner->ticketPrices()->where('id', $id)->with(['prices','route', 'fleetType'])->first();
        $page_title     = 'Ticket Prices for ' . $ticket_prices->route->name . ' (' . $ticket_prices->fleetType->name . ')';
        $owner          = $this->owner;
        return view('owner.trip.ticket.price.edit', compact('page_title', 'ticket_prices', 'owner'));
    }


    public function getRouteData(Request $request)
    {
        $route      = $this->owner->routes()->where('id', $request->route_id)->where('status', 1)->first();
        $check      = $this->owner->ticketPrices()->where('route_id', $request->route_id)->where('fleet_type_id', $request->fleet_type_id)->first();

        if($check) {
            return response()->json(['error'=> trans('You have added prices for this fleet type on this route')]);
        }

        $stoppages  = array_values($route->stoppages);

        $stoppages  = stoppageCombination($stoppages, 2);

        $owner      = $this->owner;
        return view('owner.trip.ticket.price.route_data', compact('stoppages', 'route', 'owner'));
    }

    public function store(Request $request)
    {
        $validation_rule = [
            'fleet_type'    => 'required|integer|gt:0',
            'route'         => 'required|integer|gt:0',
            'main_price'    => 'required|numeric',
            'price'         => 'sometimes|required|array|min:1',
            'price.*'       => 'sometimes|required|numeric',
        ];
        $messages = [
            'main_price'            => 'Price for Source to Destination',
            'price.*.required'      => 'All Price Fields are Required',
            'price.*.numeric'       => 'All Price Fields Should Be a Number',
        ];

        $validator = Validator::make($request->except('_token'), $validation_rule, $messages);
        $validator->validate();

        //Checking previous field
        $check = $this->owner->ticketPrices()->where('route_id', $request->route)->where('fleet_type_id', $request->fleet_type)->first();

        if($check){
            $notify[] = ['error', 'You Have Already Added Prices For This Fleet'];
            return redirect()->back()->withNotify($notify);
        }

        $create = $this->owner->ticketPrices()->create([
            'fleet_type_id'     => $request->fleet_type,
            'route_id'          => $request->route,
            'price'             => $request->main_price,
        ]);


        foreach($request->price as $key=>$val){
            $idArray = explode('-', $key);
            $prices_create = $create->prices()->create([
                'source_destination' => $idArray,
                'price'              => $val,
            ]);

        }

        $notify[] = ['success', 'All Prices Added Successfully'];
        return redirect()->back()->withNotify($notify);
    }


    public function updatePrices(Request $request, $id)
    {
        $prices = TicketPriceByStoppage::findOrFail($id);

        if($prices->mainPrice->owner_id != $this->owner->id){
            abort(401);
        }

        $request->validate([
            'price'   => 'required|numeric',
        ]);

        $prices->update([
            'price'             => $request->price,
        ]);

        $notify = ['success' => true, 'message'=>'Price Updated Successfully'];
        return response()->json($notify);
    }

    public function destroy($id)
    {
        $data = $this->owner->ticketPrices()->where('id', $id)->first();
        $data->prices()->delete();
        $data->delete();

        $notify[] = ['success', 'Price Deleted Successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function checkTicketPrice(Request $request)
    {
        $check      = $this->owner->ticketPrices()->where('route_id', $request->route_id)->where('fleet_type_id', $request->fleet_type_id)->first();

        if(!$check){
            return response()->json(['error' => 'Ticket price not added for this fleet-route combination yet. Please add ticket price before creating a trip.']);
        }
    }
}
