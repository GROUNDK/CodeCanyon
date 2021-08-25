<?php

namespace App\Http\Controllers\CounterManager;

use App\BookedTicket;
use App\Counter;
use App\Http\Controllers\Controller;
use App\TicketPrice;
use App\TicketPriceByStoppage;
use App\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Route;
use Illuminate\Support\Facades\Hash;

class CounterManagerController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->counterManager = Auth::guard('counterManager')->user();
            return $next($request);
        });
    }

    public function sell()
    {
        $data['page_title']         = 'Book Ticket';
        $data['active_package']     = $this->counterManager->owner->activePackages();

        $data['owner']              = $this->counterManager->owner;
        $data['counters']           = $this->counterManager->owner->counters()->get();
        return view('counterManager.sell.index', $data);
    }

    public function searchTrip(Request $request)
    {
        $request->validate([
            'date_of_journey'       => 'required|date|date_format:Y-m-d|after:yesterday',
            'from'                  => 'required|integer|gt:0',
            'to'                    => 'required|integer|gt:0'
        ]);

        $owner          = $this->counterManager->owner;
        $dayOff         =  Carbon::parse($request->date_of_journey)->format('w');
        $sd_array       = [$request->from, $request->to];
        $routes         = $owner
                        ->routes()->whereStatus(1)
                        ->whereJsonContains('stoppages', $sd_array)
                        ->with(['ticketPrice','ticketPrice.prices','trips'=> function($trip) use($dayOff){
                            return $trip->where('status', 1)->whereJsonDoesntContain('day_off', $dayOff);
                        }, 'trips.schedule', 'trips.bookedTickets'=>function($q) use($request){
                            return $q->where('date_of_journey', $request->date_of_journey);
                        }])
                        ->paginate(getPaginate());
        $page_title     = "Available Trips";
        $empty_message  = "No Trip Found";
        return view('counterManager.trip.index', compact('page_title', 'routes', 'empty_message', 'sd_array', 'owner'));
    }


    public function statistics()
    {
        $data['owner']              = $this->counterManager->owner;

        $routes                     = $this->counterManager->owner->routes();
        $booked_tickets             = $this->counterManager->owner->routes()->with(['bookedTickets'])->get();
        $monthlySale                = $this->counterManager->bookedTickets()
                                    ->whereMonth('created_at', date('m'))
                                    ->whereStatus(1)
                                    ->get()
                                    ->groupBy(function($date) {
                                        return Carbon::parse($date->created_at)->format('F d');
                                    });

        $yearlySale                 = $this->counterManager->bookedTickets()
                                    ->whereYear('created_at', date('Y'))
                                    ->whereStatus(1)
                                    ->get()
                                    ->groupBy(function($date) {
                                        return Carbon::parse($date->created_at)->format('F');
                                    });

        $data['daily_sale'] = $this->counterManager->bookedTickets()
                                ->whereDate('created_at', date('Y-m-d'))
                                ->whereStatus(1)
                                ->selectRaw('sum(price*ticket_count) AS total_sales , sum(ticket_count) as total_ticket')
                                ->first();
        $data['all_sale'] = $this->counterManager->bookedTickets()
                                ->whereStatus(1)
                                ->selectRaw('sum(price*ticket_count) AS total_sales , sum(ticket_count) as total_ticket')
                                ->first();


        $monthly_sale = [];
        $yearly_sale = [];

        $monthly_ticket_count       = 0;
        foreach ($monthlySale as $key => $value) {
            $s_price                    = 0;

            foreach ($value as $item) {
                $s_price                += $item->price * $item->ticket_count;
                $monthly_ticket_count   += $item->ticket_count;
            }

            $monthly_sale[$key] = $s_price;
        }

        foreach ($yearlySale as $key => $value) {
            $s_price                    = 0;
            $yearly_ticket_count        = 0;
            foreach ($value as $item) {
                $s_price                += $item->price * $item->ticket_count;
                $yearly_ticket_count   += $item->ticket_count;
            }
            $yearly_sale[$key] = $s_price;
        }


        $data['monthly_sale']   = $monthly_sale;
        $data['monthly_ticket'] = $monthly_ticket_count;

        $data['yearly_sale']   = $yearly_sale;
        $data['yearly_ticket'] = $yearly_ticket_count??0;

        $data['page_title']         = 'Your Statistics';
        return view('counterManager.statistics', $data);
    }

    public function trips()
    {
        $active_package     = $this->counterManager->owner->activePackages();
        $trips              = $this->counterManager->owner->trips()
                            ->selectRaw('trips.*, ticket_prices.id as ticket_price_id')
                            ->rightJoin('ticket_prices', function ($join) {
                            $join->on('trips.route_id', '=', 'ticket_prices.route_id')
                                    ->on('trips.fleet_type_id', '=', 'ticket_prices.fleet_type_id')
                                    ->where('trips.status', 1);
                            })->with('schedule')
                            ->whereHas('route')
                            ->paginate(getPaginate());

        $empty_message  = 'No Trip Found';
        $page_title     = 'All Trips';

        return view('counterManager.trip.index', compact('page_title', 'trips', 'empty_message', 'active_package'));
    }

    public function book($ticket_price_id, $id)
    {
        $trip               = $this->counterManager->owner
                            ->trips()->where('id', $id)
                            ->with('route', 'fleetType', 'schedule', 'bookedTickets')
                            ->first();

        $route              = $trip->route;
        $stoppages          = $trip->route->stoppages;

        if($route->starting_point == $trip->starting_point && $route->destination_point == $trip->destination_point){
            $reverse = false;
        }else{
            $reverse = true;
        }

        $booked_tickets = $trip->bookedTickets->where('date_of_journey', Carbon::now()->format('Y-m-d'));

        $ticket_prices  = TicketPriceByStoppage::where('ticket_price_id', $ticket_price_id)->get();
        $stoppageArr = $trip->route->stoppages;

        if($trip->owner_id != $this->counterManager->owner->id)
        abort(401);
        $page_title = 'Book Ticket of -'. $trip->title;

        $stoppages = Counter::routeStoppages($stoppageArr)->sortBy('name');

        $owner = $this->counterManager->owner;

        return view('counterManager.trip.book', compact('trip', 'page_title', 'stoppages', 'ticket_prices', 'booked_tickets', 'reverse', 'owner', 'ticket_price_id'));
    }



    public function bookByDate($ticket_price_id, $id)
    {
        $date               = request()->date;

        $trip               = $this->counterManager->owner
                            ->trips()->where('id', $id)
                            ->with('route', 'fleetType', 'schedule', 'bookedTickets')
                            ->first();

        $route              = $trip->route;
        $stoppages          = $trip->route->stoppages;

        if($route->starting_point == $trip->starting_point && $route->destination_point == $trip->destination_point){
            $reverse = false;
        }else{
            $reverse = true;
        }

        $booked_tickets = $trip->bookedTickets->where('date_of_journey', Carbon::parse($date)->format('Y-m-d'));

        return response()->json(['booked_seats'=>$booked_tickets]);
    }


    function getTicketPrice(Request $request){

        $ticket_price       = TicketPrice::where('route_id', $request->route_id)->where('fleet_type_id', $request->fleet_type_id)->with('route')->first();
        $route              = $ticket_price->route;
        $stoppages          = $ticket_price->route->stoppages;
        $trip               = Trip::find($request->trip_id);
        $soruce_pos         = array_search($request->source_id, $stoppages);
        $destination_pos    = array_search($request->destination_id, $stoppages);

        $booked_ticket  = $this->counterManager->owner->bookedTickets()->where('trip_id', $request->trip_id)->where('date_of_journey', Carbon::parse($request->date)->format('Y-m-d'))->get()->toArray();

        if($route->starting_point == $trip->starting_point && $route->destination_point == $trip->destination_point){
            $reverse = false;
        }else{
            $reverse = true;
        }

        if(!$reverse){
            $can_go = ($soruce_pos < $destination_pos)?true:false;
        }else{
            $can_go = ($soruce_pos > $destination_pos)?true:false;
        }

        if(!$can_go){
            $data = [
                'error' => 'Select Pickup Point & Dropping Point Properly'
            ];
            return response()->json($data);
        }
        $sdArray        = [$request->source_id, $request->destination_id];
        $getPrice = $ticket_price->prices()->where('source_destination', json_encode($sdArray))->orWhere('source_destination', json_encode(array_reverse($sdArray)))->first();

        if($getPrice){
            $price = $getPrice->price;
        }else{
            $price = [
                'error' => 'Admin may not set prices for this route. So, you can\'t sell ticket for this trip. Please contact with admin to set prices'
            ];
        }
        $data['bookedSeats']        = $booked_ticket;
        $data['req_source']         = $request->source_id;
        $data['req_destination']    = $request->destination_id;
        $data['reverse']            = $reverse;
        $data['stoppages']          = $stoppages;
        $data['price']              = $price;

        return response()->json($data);
    }

    public function booked(Request $request, $id)
    {
        $request->validate([
            "pick_up_point"         => "required|integer|gt:0",
            "dropping_point"        => "required|integer|gt:0",
            "price"                 => "required|integer",
            "name"                  => "required|string:20",
            "mobile_number"         => "required|string",
            "email"                 => "nullable|email",
            "seat_number"           => "required|string",
            "gender"                => "required|integer|digits_between:0,2",
            "date_of_journey"       => "required|date"
        ],[
            "seat_number.required"  => "Please Select at Least One Seat",
        ]);
        $date_of_journey  = Carbon::parse($request->date_of_journey);
        $today            = Carbon::today()->format('Y-m-d');
        if($date_of_journey->format('Y-m-d') < $today ){
            $notify[] = ['error', 'Date of journey cant\'t be less than today.'];
            return redirect()->back()->withNotify($notify);
        }

        $dayOff           =  $date_of_journey->format('w');
        $trip             = $this->counterManager->owner->trips()->findOrFail($id);

        if(!empty($trip->day_off)) {

            if(in_array($dayOff, $trip->day_off)) {
                $notify[] = ['error', 'The trip is not available for '.$date_of_journey->format('l')];

                return redirect()->back()->withNotify($notify);
            }
        }

        $trip               = Trip::find($id);
        $route              = $trip->route;
        $stoppages          = $trip->route->stoppages;
        $soruce_pos         = array_search($request->pick_up_point, $stoppages);
        $destination_pos    = array_search($request->dropping_point, $stoppages);

        $booked_ticket  = $this->counterManager->owner->bookedTickets()->where('trip_id', $id)->where('date_of_journey', Carbon::parse($request->date)->format('Y-m-d'))->where('pick_up_point', $request->pick_up_point)->where('dropping_point', $request->dropping_point)->whereJsonContains('seats', $request->seat_number)->get();

        if(empty($booked_ticket)){
            $notify[] = ['error', 'Why you are choosing those seats which are already booked?'];

            return redirect()->back()->withNotify($notify);
        }

        if($route->starting_point == $trip->starting_point && $route->destination_point == $trip->destination_point){
            $reverse = false;
        }else{
            $reverse = true;
        }

        if(!$reverse){
            $can_go = ($soruce_pos < $destination_pos)?true:false;
        }else{
            $can_go = ($soruce_pos > $destination_pos)?true:false;
        }

        if(!$can_go){
            $notify[] = ['error', 'Select Pickup Point & Dropping Point Properly'];

            return redirect()->back()->withNotify($notify);
        }

        $source_destination         = [$request->pick_up_point, $request->dropping_point];
        $sdInfo                     = Counter::routeStoppages($source_destination);

        $passenger['name']          = $request->name;
        $passenger['mobile_number'] = $request->mobile_number;
        $passenger['email']         = $request->email;
        $passenger['gender']        = $request->gender;

        $passenger['from']          = $sdInfo[0]->name;
        $passenger['to']            = $sdInfo[1]->name;
        $seats                      = (explode(',', $request->seat_number));

        $ticket_price               = TicketPrice::where('route_id', $trip->route_id)->where('fleet_type_id', $trip->fleet_type_id)->first();
        $sdArray                    = [$request->pick_up_point, $request->dropping_point];

        $getPrice                   = $ticket_price->prices()
                                    ->where('source_destination', json_encode($sdArray))
                                    ->orWhere('source_destination', json_encode(array_reverse($sdArray)))
                                    ->first();

        $create = $this->counterManager->owner->bookedTickets()->create([
            "counter_manager_id"=> $this->counterManager->id,
            "trip_id"           => $id,
            "pick_up_point"     => $request->pick_up_point,
            "dropping_point"    => $request->dropping_point,
            'source_destination'=> $source_destination,
            "price"             => $getPrice->price,
            "passenger_details" => $passenger,
            "ticket_count"      => sizeof($seats),
            "seats"             => $seats,
            "date_of_journey"   => $date_of_journey->format('Y-m-d')
        ]);


        if($create)
        $notify[] = ['success', 'Ticket Booked Successfully'];
        return redirect()->route('counterManager.sell.ticket.print', $create->id)->withNotify($notify);
    }

    public function cancelSold(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $now = Carbon::now();

        $booked_ticket  = BookedTicket::whereId($request->id)->with('trip', 'trip.schedule')->firstOrFail();
        $trip_time      = Carbon::parse($booked_ticket->date_of_journey.' '.$booked_ticket->trip->schedule->starts_from);

        if($booked_ticket->date_of_journey < $now && $trip_time->diffInHours($now) > 6){
            $notify[] = ['error', 'Sorry cancelation time is over'];
            return redirect()->back()->withNotify($notify);
        }

        $booked_ticket->status = 0;
        $booked_ticket->save();
        $notify[] = ['success', 'Booking Canceled Successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function rebookSold(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $now = Carbon::now();
        $booked_ticket  = BookedTicket::whereId($request->id)->with('trip', 'trip.schedule')->firstOrFail();
        $trip_time      = Carbon::parse($booked_ticket->date_of_journey.' '.$booked_ticket->trip->schedule->starts_from);

        if($booked_ticket->date_of_journey < $now && $trip_time->diffInHours($now) > 6){
            $notify[] = ['error', 'Sorry cancelation time is over'];
            return redirect()->back()->withNotify($notify);
        }

        $booked_ticket->status = 1;
        $booked_ticket->save();
        $notify[] = ['success', 'Rebooked Successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function cancelledSold()
    {
        $data['page_title']     = 'Canceled Tickets';
        $data['empty_message']  = 'No Canceled Ticket Found';

        $data['routes']         = $this->counterManager->owner->routes()->whereStatus(1)->get();
        $data['trips']          = $this->counterManager->owner->trips()->get();
        $data['owner']          = $this->counterManager->owner;


        if(request()->has('search')){
            $keyword                = ltrim(request()->search, '0');
            $data['sold_tickets']   = $this->counterManager->owner->canceledTickets()
                                ->with('trip', 'trip.route', 'counterManager')
                                ->where('id', "$keyword")
                                ->whereDate('created_at', Carbon::today())

                                ->paginate(getPaginate());
        }else{
            $data['sold_tickets']   = $this->counterManager->canceledTickets()
                            ->whereDate('created_at', Carbon::today())
                            ->with('trip', 'trip.route')
                            ->orderByDesc('id')
                            ->paginate(getPaginate());
        }

        return view('counterManager.sold_tickets.list', $data);
    }


    public function ticketPrint($id)
    {
        $ticket = BookedTicket::whereId($id)->with('trip', 'trip.route', 'trip.schedule', 'trip.owner')->firstorFail();
        $page_title = 'Print Ticket';
        return view('counterManager.trip.ticket', compact('ticket', 'page_title'));
    }

    public function todaysSold(){
        $data['page_title']     = 'Today\'s Sold Tickets';
        $data['empty_message']  = 'No ticket sold by you for today';

        $data['routes']         = $this->counterManager->owner->routes()->whereStatus(1)->get();
        $data['trips']          = $this->counterManager->owner->trips()->get();
        $data['owner']          = $this->counterManager->owner;

        if(request()->has('search')){
            $keyword            = ltrim(request()->search, '0');
            $data['sold_tickets'] = $this->counterManager->owner->bookedTickets()
                                ->with('trip', 'trip.route', 'counterManager')
                                ->where('id', "$keyword")
                                ->whereDate('created_at', Carbon::today())

                                ->paginate(getPaginate());
        }else{
            $data['sold_tickets']    = $this->counterManager->bookedTickets()
                            ->whereDate('created_at', Carbon::today())
                            ->with('trip', 'trip.route')
                            ->orderByDesc('id')
                            ->paginate(getPaginate());
        }

        return view('counterManager.sold_tickets.list', $data);

    }
    public function allSold(){
        $data['page_title']     = 'All Sold Tickets';
        $data['empty_message']  = 'No ticket sold by you for today';
        $data['routes']         = $this->counterManager->owner->routes()->whereStatus(1)->get();
        $data['trips']          = $this->counterManager->owner->trips()->get();
        $data['owner']          = $this->counterManager->owner;

        if(request()->has('search')){
            $keyword            = ltrim(request()->search, '0');
            $data['sold_tickets']      = $this->counterManager->owner->bookedTickets()
                                ->with('trip', 'trip.route', 'counterManager')
                                ->where('id', "$keyword")
                                ->paginate(getPaginate());
        }else{
            $data['sold_tickets']   = $this->counterManager->bookedTickets()
                            ->with('trip', 'trip.route')
                            ->orderByDesc('id')
                            ->paginate(getPaginate());
        }

        return view('counterManager.sold_tickets.list', $data);

    }

    public function filter(Request $request)
    {
        $request->validate([
            'booking_date'      => 'nullable|date_format:Y-m-d',
            'date_of_journey'   => 'nullable|date_format:Y-m-d',
            'year'              => 'nullable|date_format:Y',
            'month'             => 'nullable|string',
            'date_to_date'      => 'nullable|string',
            'booked_by'         => 'nullable|integer|gt:0',
            'route'             => 'nullable|integer|gt:0',
            'trip'              => 'nullable|integer|gt:0',
        ]);

        $input          = $request->except('_token');
        $input          = array_filter($input);
        $count          = count($input);
        $data['title']  = [];

        if($count < 1) {
            $notify[]   = ['error', 'Please select at least one field.'];
            return redirect()->back()->withNotify($notify);
        }

        $query = $this->counterManager->owner->bookedTickets();

        if(array_key_exists('booking_date', $input)){
            $data['title']['Booking Date']     = Carbon::parse($request->booking_date)->format('F d, Y');
            $query->whereDate('booked_tickets.created_at', $request->booking_date);
        }
        if(array_key_exists('date_of_journey', $input)){
            $data['title']['Date of journey']     = Carbon::parse($request->date_of_journey)->format('F d, Y');
            $query->whereDate('booked_tickets.date_of_journey', $request->date_of_journey);
        }
        if(array_key_exists('year', $input)){
            $data['title']['Year']     = Carbon::parse($request->year)->format('F d, Y');
            $query->whereYear('booked_tickets.created_at', $request->year);
        }
        if(array_key_exists('month', $input)){
            $data['title']['Month']     = Carbon::parse($request->month)->format('F d, Y');
            $month  = Carbon::parse($request->month)->format('m');
            $query->whereMonth('booked_tickets.created_at', $month);
        }

        if(array_key_exists('booked_by', $input)){

            $data['title']['Booked by']      = CounterManager::find($request->booked_by)->name;
            $query->where('cbooked_tickets.ounter_manager_id', $request->booked_by);
        }

        if(array_key_exists('trip', $input)){
            $data['title']['Trip']      = Trip::find($request->trip)->title;
            $query->where('trip_id', $request->trip);
        }

        if(array_key_exists('route', $input)){
            $data['title']["Route"]      = Route::find($request->route)->name;
            $query->leftjoin('trips', 'trips.id', '=', 'booked_tickets.trip_id')
            ->leftjoin('routes', 'routes.id', '=', 'trips.route_id')->where('routes.id',$request->route);
        }


        if(array_key_exists('date_to_date', $input)){
            $data['title']['Date: ']      = $request->date_to_date ;
            $date               = explode('to', $request->date_to_date);
            $count              = count($date);
            if($count < 2) {
                $notify[]       = ['error', 'Date to Date must have two date.'];
                return redirect()->back()->withNotify($notify);
            }
            $start_date         = trim($date[0]);
            $end_date           = trim($date[1]);

            $query->whereBetween('booked_tickets.created_at', [$start_date, $end_date]);
        }

        $query->paginate(getPaginate());

        $data['sales'] = $query->paginate(getPaginate());
        return redirect()->route('counterManager.soldTickets.filtered')->with( ['filteredData' => $data] );

    }

    public function filtered(Request $request)
    {
        $sales = session('filteredData')['sales']??null;

        if($sales == null){
            return redirect()->route('counterManager.soldTickets.all');
        }
        $data['owner']          = $this->counterManager->owner;
        $data['sold_tickets']          = $sales;
        $data['page_title']     = 'All Sales';
        $data['title']          = session('filteredData')['title']??'';
        $data['empty_message']  = "No data found";
        $data['owner']          = $this->counterManager->owner;
        $data['routes']         = $this->counterManager->owner->routes()->whereStatus(1)->get();
        $data['counterManagers']= $this->counterManager->owner->counterManagers()->with('counter')->where('status', 1)->orderBy('name')->get();
        $data['trips']          = $this->counterManager->owner->trips()->get();
        return view('counterManager.sold_tickets.list', $data);
    }


    public function profile()
    {
        $page_title = 'Profile';
        $counterManager = $this->counterManager;
        return view('counterManager.profile', compact('page_title', 'counterManager'));
    }

    public function profileUpdate(Request $request)
    {
        $counterManager = $this->counterManager;

        $this->validate($request, [
            'name'    => 'required|string|max:50',
            'username'      => 'required|alpha_num|min:5|unique:owners,username,'.$counterManager->id,
            'email'         => 'required|string|email|max:90|unique:owners,email,'.$counterManager->id,
            'mobile'        => 'required|string|max:50|unique:owners,mobile,'.$counterManager->id,
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
                $old = $counterManager->image ?: null;
                $counterManager->image = uploadImage($request->image, 'assets/owner/images/counterManager/', '400X400', $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $counterManager->name         = $request->name;
        $counterManager->username     = $request->username;
        $counterManager->email        = $request->email;
        $counterManager->mobile       = $request->mobile;
        $counterManager->address      = $address;

        $counterManager->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('counterManager.profile')->withNotify($notify);
    }

    public function password()
    {
        $page_title = 'Password Setting';
        $counterManager = $this->counterManager;
        return view('counterManager.password', compact('page_title', 'counterManager'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password'  => 'required',
            'password'      => 'required|min:6|confirmed',
        ]);

        $user = $this->counterManager;
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('counterManager.password')->withNotify($notify);
    }
}
