<?php

namespace App\Http\Middleware;

use App\GeneralSetting;
use Closure;
use Illuminate\Support\Facades\Auth;

class checkPackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'owner')
    {
        $general = GeneralSetting::first();
        if($guard == 'owner'){
            $owner = Auth::guard('owner')->user();
        }

        elseif($guard == 'co-owner'){
            $owner = Auth::guard('co-owner')->user()->owner;
        }
        elseif($guard == 'counterManager'){
            $owner = Auth::guard('counterManager')->user()->owner;
        }



        $notify[]=['error','You don\'t have any active package to access this menu.'];
        $packageCount = $owner->activePackages()->count();
        if($packageCount == 0 && $general->package_id == null){
            return redirect()->route("$guard.dashboard")->withNotify($notify);
        }

        if($packageCount == 0){

            $package    = $general->package;
            $start_from = $owner->created_at;

            $ends_at    = getPackageExpireDate($package->time_limit, $package->unit, $start_from);

            if($ends_at < \Carbon\Carbon::now()){
                return redirect()->route("$guard.dashboard")->withNotify();
            }
        }


        return $next($request);
    }
}
