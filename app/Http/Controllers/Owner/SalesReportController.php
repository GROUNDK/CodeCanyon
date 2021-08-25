<?php

namespace App\Http\Controllers\Owner;

use App\CounterManager;
use App\Http\Controllers\Controller;
use App\Route;
use App\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReportController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->owner = Auth::guard('owner')->user();
            return $next($request);
        });
    }

    public function sale()
    {
        $data['page_title']     = "All Sales";
        if(request()->has('search') && request()->search != ''){
            $keyword            = ltrim(request()->search, '0');
            $data['sales']      = $this->owner->bookedTickets()
                                ->with('trip', 'trip.route', 'counterManager')
                                ->where('id', "$keyword")
                                ->whereStatus(1)->paginate(getPaginate());
        }else{
            $data['sales']          = $this->owner->bookedTickets()->with('trip', 'trip.route', 'counterManager')->whereStatus(1)->paginate(getPaginate());
        }
        $data['empty_message']  = "No data found";
        $data['owner']          = $this->owner;
        $data['routes']         = $this->owner->routes()->whereStatus(1)->get();
        $data['trips']          = $this->owner->trips()->get();
        $data['counterManagers']= $this->owner->counterManagers()->with('counter')->where('status', 1)->orderBy('name')->get();
        return view('owner.report.sale', $data);
    }

    public function saleDetail($id)
    {
        $data['page_title']     = "Booking Details";
        $data['sale']           = $this->owner->bookedTickets()->with('trip', 'trip.route', 'counterManager')->whereId($id)->whereStatus(1)->first();
        $data['empty_message']  = "No data found";
        $data['owner']          = $this->owner;
        return view('owner.report.sale_detail', $data);
    }

    public function filterSales(Request $request)
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

        $query = $this->owner->bookedTickets();

        if(array_key_exists('booking_date', $input)){
            $data['title']['Booking Date']     = Carbon::parse($request->booking_date)->format('F d, Y');
            $query->whereDate('booked_tickets.created_at', $request->booking_date);
        }
        if(array_key_exists('date_of_journey', $input)){
            $data['title']['Date of journey']     = Carbon::parse($request->date_of_journey)->format('F d, Y');
            $query->whereDate('date_of_journey', $request->date_of_journey);
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
        if(array_key_exists('date_to_date', $input)){
            $data['title']['Date to Date']      = $request->date_to_date ;
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
        if(array_key_exists('booked_by', $input)){

            $data['title']['Booked by']      = CounterManager::find($request->booked_by)->name;
            $query->where('counter_manager_id', $request->booked_by);
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

        $query->where('booked_tickets.status', 1);
        $query->paginate(getPaginate());
        $data['sales'] = $query->paginate(getPaginate());
        return redirect()->route('owner.report.sale.filtered')->with( ['filteredData' => $data] );

    }

    public function filteredData(Request $request)
    {
        $sales = session('filteredData')['sales']??null;

        if($sales == null){
            return redirect()->route('owner.report.sale');
        }

        $data['sales']          = $sales;
        $data['page_title']     = 'All Sales';
        $data['title']          = session('filteredData')['title']??'';
        $data['empty_message']  = "No data found";
        $data['owner']          = $this->owner;
        $data['routes']         = $this->owner->routes()->whereStatus(1)->get();
        $data['counterManagers']= $this->owner->counterManagers()->with('counter')->where('status', 1)->orderBy('name')->get();
        $data['trips']          = $this->owner->trips()->get();
        return view('owner.report.sale', $data);

    }

}
