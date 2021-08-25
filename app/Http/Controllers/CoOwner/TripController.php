<?php

namespace App\Http\Controllers\CoOwner;

use App\AssignedBus;
use App\Bus;
use App\Http\Controllers\Controller;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->co_owner = Auth::guard('co-owner')->user();
            return $next($request);
        });
    }

    public function index()
    {
        if(request()->has('search') && request()->search != ''){
            $keyword = request()->search;
            $trips      = $this->co_owner->owner->trips()->where('title', 'like', "%$keyword%")->with(['fleetType', 'route', 'schedule'])->paginate(getPaginate());
        }else
        $trips      = $this->co_owner->owner->trips()->with(['fleetType', 'route', 'schedule'])->paginate(getPaginate());

        $page_title = 'All Trips';

        $fleet_types= $this->co_owner->owner->fleetTypes()->where('status', 1)->get();
        $schedules  = $this->co_owner->owner->schedules;
        $routes     = $this->co_owner->owner->routes()->where('status', 1)->get();
        $empty_message = 'No Trip Have Been Ceated Yet';

        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.trip.index', compact('page_title', 'trips', 'empty_message', 'fleet_types', 'schedules', 'routes', 'owner', 'co_owner'));
    }

    public function trashed()
    {
        $page_title = 'All Trips';
        if(request()->has('search') && request()->search != ''){
            $keyword = request()->search;
            $trips      = $this->co_owner->owner->trips()->onlyTrashed()->where('title', 'like', "%$keyword%")->with(['fleetType', 'route', 'schedule'])->paginate(getPaginate());
        }else
        $trips      = $this->co_owner->owner->trips()->onlyTrashed()->with(['fleetType', 'route', 'schedule'])->paginate(getPaginate());

        $fleet_types= $this->co_owner->owner->fleetTypes()->where('status', 1)->get();
        $schedules  = $this->co_owner->owner->schedules;
        $routes     = $this->co_owner->owner->routes()->where('status', 1)->get();
        $empty_message = 'No Trip Found';

        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.trip.index', compact('page_title', 'trips', 'empty_message', 'fleet_types', 'schedules', 'routes', 'owner', 'co_owner'));
    }

    public function create()
    {
        $page_title     = "Add New Trip";
        $fleet_types= $this->co_owner->owner->fleetTypes()->where('status', 1)->get();
        $schedules  = $this->co_owner->owner->schedules;
        $routes     = $this->co_owner->owner->routes()->where('status', 1)->get();
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.trip.create', compact('page_title', 'schedules', 'fleet_types', 'schedules', 'routes', 'owner', 'co_owner'));
    }
    public function edit($id)
    {
        $trip = $this->co_owner->owner->trips()->where('id', $id)->firstOrFail();
        $page_title     = "Update Trip : " . $trip->title;
        $fleet_types= $this->co_owner->owner->fleetTypes()->where('status', 1)->get();
        $schedules  = $this->co_owner->owner->schedules;
        $routes     = $this->co_owner->owner->routes()->where('status', 1)->get();
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.trip.create', compact('page_title',  'fleet_types', 'schedules', 'routes', 'trip', 'owner', 'co_owner'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'title'         => 'required|string',
            'fleet_type'    => 'required|integer|gt:0',
            'schedule'      => 'required|integer|gt:0',
            'from'          => 'required|integer|gt:0',
            'to'            => 'required|integer|gt:0',
            'route'         => 'required|integer|gt:0',
            'schedule'      => 'required|integer|gt:0',
            'day_off'       => 'nullable|array|min:1'
        ]);

        if($id == 0){
            $create = $this->co_owner->owner->trips()->create([
                'title'             => $request->title,
                'fleet_type_id'     => $request->fleet_type,
                'route_id'          => $request->route,
                'schedule_id'       => $request->schedule,
                'starting_point'    => $request->from,
                'destination_point' => $request->to,
                'day_off'           => $request->day_off??[],
                'status'            => $request->status?1:0
            ]);
            if($create)
            $notify[] = ['success', 'New Trip Added Successfully'];

        }else{
            $update = $this->co_owner->owner->trips()->where('id', $id)->first()->update([
                'title'             => $request->title,
                'fleet_type_id'     => $request->fleet_type,
                'schedule_id'       => $request->schedule,
                'route_id'          => $request->route,
                'starting_point'    => $request->from,
                'destination_point' => $request->to,
                'day_off'           => $request->day_off??[],
                'status'            => $request->status?1:0
            ]);

            if($update)
            $notify[] = ['success', 'Trip Updated Successfully'];
        }
        return redirect()->back()->withNotify($notify);
    }


    public function remove($id)
    {
        $trip = $this->co_owner->owner->trips()->where('id', $id)->withTrashed()->first();

        if ($trip->trashed()){
            $trip->restore();
            $notify[] = ['success', 'Trip Restored Successfully'];
            return redirect()->back()->withNotify($notify);
        }else{
            $trip->delete();
            $notify[] = ['success', 'Trip Deleted Successfully'];
            return redirect()->back()->withNotify($notify);
        }
    }

    /*
    ============Route============
    */

    public function route()
    {
        $page_title     = "All Routes";
        $empty_message  = 'No Route Added Yet';
        $routes         = $this->co_owner->owner->routes()->with(['startingPoint', 'destinationPoint'])->get();
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        $stoppages      = $this->co_owner->owner->counters()->where('status', 1)->get();
        return view('co-owner.trip.route.index', compact('empty_message', 'page_title', 'routes', 'stoppages', 'owner', 'co_owner'));
    }

    public function routeCreate()
    {
        $page_title     = "Add New Route";
        $stoppages      = $this->co_owner->owner->counters()->where('status', 1)->get();
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.trip.route.create', compact('page_title', 'stoppages', 'owner', 'co_owner'));
    }

    public function routeEdit($id)
    {

        $page_title     = "Edit Route";
        $route          = $this->co_owner->owner->routes()->where('id', $id)->with(['startingPoint', 'destinationPoint'])->first();
        $allStoppages   = $this->co_owner->owner->counters()->where('status', 1)->get();

        $stoppagesArray = $route->stoppages;

        $pos = array_search($route->starting_point, $stoppagesArray);
        unset($stoppagesArray[$pos]);
        $pos = array_search($route->destination_point, $stoppagesArray);
        unset($stoppagesArray[$pos]);

        if(!empty($stoppagesArray)){

            $stoppages = $this->co_owner->owner->counters()
                ->where('status', 1)->whereIn('id', $stoppagesArray)
                ->orderByRaw("field(id,".implode(',',$stoppagesArray).")")
                ->get();
        }else{
            $stoppages = [];
        }


        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.trip.route.edit', compact('page_title', 'stoppages', 'route', 'allStoppages', 'owner', 'co_owner'));
    }

    public function routeTrashed ()
    {
        $page_title     = "Trashed Routes";
        $empty_message  = 'No Trashed Route Found';
        $stoppages      = $this->co_owner->owner->counters()->where('status', 1)->get();
        $routes         = $this->co_owner->owner->routes()->with(['startingPoint', 'destinationPoint'])->onlyTrashed()->get();
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.trip.route.index', compact('empty_message', 'page_title', 'routes', 'stoppages', 'owner', 'co_owner'));
    }

    public function routeStore(Request $request, $id)
    {

        $request->validate([
            'name'              => 'required|string|max:255',
            'starting_point'    => 'required|integer|gt:0',
            'destination_point' => 'required|integer|gt:0',
            'stoppages'         => 'nullable|array|min:1',
            'stoppages.*'       => 'nullable|numeric|gt:0',
            'distance'          => 'nullable|string|max:30',
            'time'              => 'nullable|string|max:30'
        ],[
            'stoppages.*.numeric'       => 'Invalid Stoppage Field'
        ]);

        if($request->starting_point == $request->destination_point){
            $notify[] = ['error', 'Starting Point and Destination Point Can\'t Be Same'];
            return redirect()->back()->withNotify($notify);
        }

        $stoppages = $request->stoppages?array_filter($request->stoppages):[];

        if (!in_array($request->starting_point, $stoppages)) {
            array_unshift($stoppages, $request->starting_point);
        }

        if (!in_array($request->destination_point, $stoppages)) {
            array_push($stoppages, $request->destination_point);
        }


        if($id == 0){
            $create = $this->co_owner->owner->routes()->create([
                'name'              => $request->name,
                'starting_point'    => $request->starting_point,
                'destination_point' => $request->destination_point,
                'stoppages'         => array_unique($stoppages),
                'distance'          => $request->distance,
                'time'              => $request->time,
                'status'            => $request->status?1:0
            ]);
            if($create)
            $notify[] = ['success', 'New Route Added Successfully'];

        }else{
            $update = $this->co_owner->owner->routes()->where('id', $id)->first()->update([
                'name'              => $request->name,
                'starting_point'    => $request->starting_point,
                'destination_point' => $request->destination_point,
                'stoppages'         => $stoppages,
                'distance'          => $request->distance,
                'time'              => $request->time,
                'status'            => $request->status?1:0
            ]);

            if($update)
            $notify[] = ['success', 'Route Updated Successfully'];
        }
        return redirect()->back()->withNotify($notify);
    }


    public function routeRemove($id)
    {
        $route = $this->co_owner->owner->routes()->where('id', $id)->withTrashed()->first();

        if ($route->trashed()){
            $route->restore();
            $notify[] = ['success', 'Route Restored Successfully'];
            return redirect()->back()->withNotify($notify);
        }else{
            $route->delete();
            $notify[] = ['success', 'Route Deleted Successfully'];
            return redirect()->back()->withNotify($notify);
        }
    }

    //Bus Assignment
    public function bus()
    {
        $page_title     = 'All Assigned Buses';

        $trips   = $this->co_owner->owner->trips()->with(['fleetType', 'fleetType.buses' => function($q){
            return $q->where('status', 1);
        }])->where('status', 1)->get();

        $drivers        = $this->co_owner->owner->drivers()->where('status', 1)->get();
        $supervisors    = $this->co_owner->owner->supervisors()->where('status', 1)->get();

        if(request()->has('search') && request()->search != ''){
            $keyword = request()->search;

            $assigned_buses = $this->co_owner->owner->assignedBuses()
                ->whereHas('trip', function($trip) use($keyword){
                return $trip->where('title', 'like',"%$keyword%");
            })

            ->orWhereHas('bus', function($bus) use($keyword){

                return $bus->where('registration_no', 'like' ,"%$keyword%");
            })
            ->orWhereHas('driver', function($driver) use($keyword){

                return $driver->where('name', 'like' ,"%$keyword%");
            })
            ->orWhereHas('supervisor', function($supervisor) use($keyword){

                return $supervisor->where('name', 'like' ,"%$keyword%");
            })

            ->with(['bus', 'trip', 'supervisor', 'driver'])
            ->paginate(getPaginate());
        }else
        $assigned_buses = $this->co_owner->owner->assignedBuses()
        ->with([
            'bus',
            'trip',
            'supervisor' => function($q){
                return $q->withTrashed();
            }, 'driver'=> function($q){
                return $q->withTrashed();
        }])
        ->paginate(getPaginate());

        $fleet_types    = $this->co_owner->owner->fleetTypes()->where('status', 1)->get();
        $schedules      = $this->co_owner->owner->schedules;
        $routes         = $this->co_owner->owner->routes()->where('status', 1)->get();
        $empty_message  = 'No Trip Found';
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;

        return view('co-owner.trip.bus.index', compact('page_title', 'trips', 'empty_message', 'fleet_types', 'schedules', 'routes', 'assigned_buses', 'drivers', 'supervisors', 'owner', 'co_owner'));
    }
    //Bus Assignment
    public function busCreate()
    {
        $page_title     = 'Assign Bus To Trip';

        $trips   = $this->co_owner->owner->trips()->with(['fleetType', 'fleetType.buses' => function($q){
            return $q->where('status', 1);
        }])->where('status', 1)->get();

        $drivers        = $this->co_owner->owner->drivers()->where('status', 1)->get();
        $supervisors    = $this->co_owner->owner->supervisors()->where('status', 1)->get();
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;
        return view('co-owner.trip.bus.create', compact('page_title', 'trips', 'drivers', 'supervisors', 'owner', 'co_owner'));
    }

    public function busEdit($id)
    {
        $data['bus']            = AssignedBus::findOrFail($id);
        $data['page_title']     = 'Edit Assigned Bus For Trip';

        $data['trips']          = $this->co_owner->owner->trips()->with(['fleetType', 'fleetType.buses' => function($q){
                                    return $q->where('status', 1);
                                }])->where('status', 1)->get();

        $data['drivers']        = $this->co_owner->owner->drivers()->where('status', 1)->get();
        $data['supervisors']    = $this->co_owner->owner->supervisors()->where('status', 1)->get();
        $data['owner']          = $this->co_owner->owner;
        $data['co_owner']       = $this->co_owner;
        return view('co-owner.trip.bus.create', $data);
    }

    public function busTrashed()
    {
        $page_title     = 'All Assigned Buses';
        $trips          = $this->co_owner->owner->trips()->where('status', 1)->get();
        $buses          = $this->co_owner->owner->buses()->where('status', 1)->get();
        $drivers        = $this->co_owner->owner->drivers()->where('status', 1)->get();
        $supervisors    = $this->co_owner->owner->supervisors()->where('status', 1)->get();

        $assigned_buses = $this->co_owner->owner->assignedBuses()->onlyTrashed()->with([
            'bus',
            'trip',
            'supervisor' => function($q){
                return $q->withTrashed();
            }, 'driver'=> function($q){
                return $q->withTrashed();
        }])
        ->paginate(getPaginate());
        $fleet_types    = $this->co_owner->owner->fleetTypes()->where('status', 1)->get();
        $schedules      = $this->co_owner->owner->schedules;
        $routes         = $this->co_owner->owner->routes()->where('status', 1)->get();
        $empty_message  = 'No Trip Have Been Ceated Yet';
        $owner          = $this->co_owner->owner;
        $co_owner       = $this->co_owner;

        return view('co-owner.trip.bus.index', compact('page_title', 'trips', 'empty_message', 'routes', 'assigned_buses', 'buses', 'drivers', 'supervisors', 'owner', 'co_owner'));
    }

    public function busStore(Request $request, $id)
    {

        $request->validate([
            'trip'                      => 'required|string',
            'bus_registration_number'   => 'required|integer|gt:0',
            'driver'                    => 'required|integer|gt:0',
            'supervisor'                => 'required|integer|gt:0'
        ]);

        //Check if the trip has already a assigned bus;
        $trip_check = $this->co_owner->owner->assignedBuses()->where('id', '!=',$id)->where('trip_id', $request->trip)->first();

        if($trip_check){
            $notify[]=['error','A vehicle had already been assinged to this trip'];
            return back()->withNotify($notify);
        }

        $trip       = Trip::where('id', $request->trip)->with('schedule')->firstOrFail();

        $start_time = Carbon::parse($trip->schedule->starts_from)->format('H:i:s');
        $end_time   = Carbon::parse($trip->schedule->ends_at)->format('H:i:s');

        //Check if the bus assgined to another bus on this time
        $bus_check = $this->co_owner->owner->assignedBuses()

                    ->where(function($q) use($start_time,$end_time, $id, $request){
                        $q->where('starts_from','>',$start_time)
                        ->where('starts_from','<',$end_time)
                        ->where('id', '!=', $id)
                        ->where('bus_id', $request->bus_registration_number);
                    })
                    ->orWhere(function($q) use($start_time,$end_time, $id, $request){
                        $q->where('ends_at','>',$start_time)
                        ->where('ends_at','<',$end_time)
                        ->where('id', '!=', $id)
                        ->where('bus_id', $request->bus_registration_number);
                    })
                    ->first();


        if($bus_check){
            $notify[]=['error','This vehicle had already been assinged to another trip on this time'];
            return back()->withNotify($notify);
        }

        //Check if the driver assgined to another bus on this time
        $driver_check = $this->co_owner->owner->assignedBuses()
                        ->where(function($q) use($start_time,$end_time, $id, $request){
                            $q->where('starts_from','>',$start_time)
                            ->where('starts_from','<',$end_time)
                            ->where('driver_id', $request->driver)
                            ->where('id', '!=', $id);
                        })
                        ->orWhere(function($q) use($start_time,$end_time, $id, $request){
                            $q->where('ends_at','>',$start_time)
                            ->where('ends_at','<',$end_time)
                            ->where('driver_id', $request->driver)
                            ->where('id', '!=', $id);
                        })
                        ->first();
        if($driver_check){
            $notify[]=['error','This driver had already been assinged to another trip on this time'];
            return back()->withNotify($notify);
        }

        //Check if the supervisor assgined to another bus on this time
        $supervisor_check = $this->co_owner->owner->assignedBuses()

                        ->where(function($q) use($start_time,$end_time, $id, $request){
                            $q->where('starts_from','>',$start_time)
                            ->where('starts_from','<',$end_time)
                            ->where('id', '!=', $id)
                            ->where('supervisor_id', $request->supervisor);
                        })
                        ->orWhere(function($q) use($start_time,$end_time, $id, $request){
                            $q->where('ends_at','>',$start_time)
                            ->where('ends_at','<',$end_time)
                            ->where('id', '!=', $id)
                            ->where('supervisor_id', $request->supervisor);
                        })
                        ->first();

        if($supervisor_check){
            $notify[]=['error','This supervisor had already been assinged to another trip on this time'];
            return back()->withNotify($notify);
        }

        if($id == 0){
            $create = $this->co_owner->owner->assignedBuses()->create([
                'trip_id'       => $request->trip,
                'bus_id'        => $request->bus_registration_number,
                'driver_id'     => $request->driver,
                'supervisor_id' => $request->supervisor,
                'status'        => $request->status?1:0,
                'starts_from'   => $trip->schedule->starts_from,
                'ends_at'       => $trip->schedule->ends_at
            ]);
            if($create)
            $notify[] = ['success', 'Bus Assigned Successfully'];

        }else{
            $update = $this->co_owner->owner->assignedBuses()->where('id', $id)->first()->update([
                'trip_id'          => $request->trip,
                'bus_id'        => $request->bus_registration_number,
                'driver_id'     => $request->driver,
                'supervisor_id' => $request->supervisor,
                'status'        => $request->status?1:0,
                'starts_from'   => $trip->schedule->starts_from,
                'ends_at'       => $trip->schedule->ends_at
            ]);

            if($update)
            $notify[] = ['success', 'Data Updated Successfully'];
        }
        return redirect()->back()->withNotify($notify);
    }

    public function busRemove($id)
    {
        $bus = $this->co_owner->owner->assignedBuses()->where('id', $id)->withTrashed()->first();

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
