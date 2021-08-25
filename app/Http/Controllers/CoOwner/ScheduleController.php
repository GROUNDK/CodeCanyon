<?php

namespace App\Http\Controllers\CoOwner;

use App\Http\Controllers\Controller;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->co_owner = Auth::guard('co-owner')->user();
            return $next($request);
        });
    }

    public function index()
    {
        $page_title     = "All Schedules";
        $schedules      = $this->co_owner->owner->schedules()->paginate(getPaginate());
        $empty_message  = "No schedule Have Been Ceated Yet";
        $owner      = $this->co_owner->owner;
        $co_owner   = $this->co_owner;
        return view('co-owner.schedule.index', compact('page_title', 'empty_message', 'schedules', 'owner', 'co_owner'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'starts_from'   => 'required|date_format:H:i',
            'ends_at'       => 'required|date_format:H:i',
        ]);

        $check = $this->co_owner->owner->schedules()->where('starts_from', Carbon::parse($request->starts_from)->format('H:i:s'))->where('ends_at', Carbon::parse($request->ends_at)->format('H:i:s'))->first();


        if($id == 0){

            if($check){
                $notify[] = ['error', 'This Schedule Has Already Added'];
                return redirect()->back()->withNotify($notify);
            }

            $create = $this->co_owner->owner->schedules()->create([
                'starts_from'       => $request->starts_from,
                'ends_at'           => $request->ends_at,
            ]);

            if($create)
            $notify[] = ['success', 'New Schedule Added Successfully'];

        }else{

            if($check && $check->id != $id){
                $notify[] = ['error', 'This Schedule Has Already Added'];
                return redirect()->back()->withNotify($notify);
            }
            $update = $this->co_owner->owner->schedules()->where('id', $id)->first()->update([
                'starts_from'       => $request->starts_from,
                'ends_at'           => $request->ends_at,
            ]);

            if($update)
            $notify[] = ['success', 'Schedule Updated Successfully'];
        }
        return redirect()->back()->withNotify($notify);
    }

    public function destroy($id)
    {

        $schedule = $this->co_owner->owner->schedules()->where('id', $id)->with('trips')->first();
        if(!$schedule->trips){
            $schedule->delete();
            $notify[] = ['success', 'Schedule Deleted Successfully'];
        }else{
            $notify[] = ['error', 'You Can\'t Delete This Schedule. Because it has some trips'];
        }

        return redirect()->back()->withNotify($notify);

    }
}
