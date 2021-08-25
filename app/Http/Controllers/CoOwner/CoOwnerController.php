<?php

namespace App\Http\Controllers\CoOwner;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CoOwnerController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->coOwner = Auth::guard('co-owner')->user();
            return $next($request);
        });
    }

    public function dashboard()
    {
        $data['page_title'] = "CoOwner Dashboard";
        $active_packages    = $this->coOwner->owner->activePackages();
        $data['owner']      = $this->coOwner->owner;
        $data['co_owner']   = $this->coOwner;
        $routes             = $this->coOwner->owner->routes();
        $booked_tickets     = $this->coOwner->owner->routes()->with(['bookedTickets' => function($bt){
            $bt->selectRaw('booked_tickets.price * booked_tickets.ticket_count as total_amount')->where('booked_tickets.status', 1);
        }])->get();

        $monthlySale        = $this->coOwner->owner->bookedTickets()
                            ->whereMonth('created_at', date('m'))
                            ->get()
                            ->groupBy(function($date) {
                                return Carbon::parse($date->created_at)->format('F d');
                            });

        $monthly_sale['date']   = $monthlySale->keys();
        $monthly_sale['amount'] = collect([]);
        $monthlySale->map(function($ms) use($monthly_sale){
            $monthly_sale['amount']->push($ms->sum('price'));
        });

        $booked_ticket['route_name'] = collect([]);
        $booked_ticket['sale_price'] = collect([]);

        $booked_tickets->map(function($bt) use($booked_ticket){
            $booked_ticket['route_name']->push($bt->name);
            $booked_ticket['sale_price']->push($bt->bookedTickets->sum('total_amount'));
        });

        $data['total_bus']          = $this->coOwner->owner->buses()->count();
        $data['total_driver']       = $this->coOwner->owner->drivers()->count();
        $data['total_supervisor']   = $this->coOwner->owner->supervisors()->count();
        $data['total_coAdmin']      = $this->coOwner->owner->coAdmins()->count();
        $data['total_counter']      = $this->coOwner->owner->counters()->count();
        $data['total_cManager']     = $this->coOwner->owner->counterManagers()->count();
        $data['total_route']        = $routes->count();
        $data['total_trip']         = $this->coOwner->owner->trips()->count();
        $data['active_packages']    = $active_packages;

        return view('co-owner.dashboard', $data, compact('booked_ticket', 'monthly_sale'));
    }

    public function profile()
    {
        $page_title = 'Profile';
        $co_owner   = $this->coOwner;
        $owner      = $this->coOwner->owner;
        return view('co-owner.profile', compact('page_title', 'co_owner', 'owner'));
    }

    public function profileUpdate(Request $request)
    {
        $coowner = $this->coOwner;

        $this->validate($request, [
            'name'          => 'required|string|max:50',
            'username'      => 'required|alpha_num|min:5|unique:owners,username,'.$coowner->id,
            'email'         => 'required|string|email|max:90|unique:owners,email,'.$coowner->id,
            'mobile'        => 'required|string|max:50|unique:owners,mobile,'.$coowner->id,
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
                $old = $coowner->image ?: null;
                $coowner->image = uploadImage($request->image, 'assets/owner/images/co-owner/', '400X400', $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $coowner->name         = $request->name;
        $coowner->username     = $request->username;
        $coowner->email        = $request->email;
        $coowner->mobile       = $request->mobile;
        $coowner->address      = $address;
        $coowner->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('co-owner.profile')->withNotify($notify);
    }

    public function password()
    {
        $page_title = 'Password Setting';
        $co_owner   = $this->coOwner;
        $owner      = $this->coOwner->owner;

        return view('co-owner.password', compact('page_title', 'co_owner', 'owner'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password'  => 'required',
            'password'      => 'required|min:6|confirmed',
        ]);

        $user = $this->coOwner;
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('co-owner.password')->withNotify($notify);
    }

}
