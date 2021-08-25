<?php

namespace App\Http\Controllers\Owner;

use App\BookedTicket;
use App\Feature;
use App\Http\Controllers\Controller;
use App\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class OwnerController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->owner = Auth::guard('owner')->user();
            return $next($request);
        });
    }

    public function dashboard()
    {
        $page_title                 = 'Owner Dashboard';

        $active_packages            = $this->owner->activePackages();

        $data['owner']              = $this->owner;


        $routes = $this->owner->routes();
        $booked_tickets = $this->owner->routes()->with(['bookedTickets' => function($bt){
            $bt->selectRaw('booked_tickets.price * booked_tickets.ticket_count as total_amount')->where('booked_tickets.status', 1);
        }])->get();

        $monthlySale = $this->owner->bookedTickets()
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

        $data['page_title']         = 'Owner Dashboard';
        $data['total_bus']          = $this->owner->buses()->count();
        $data['total_driver']       = $this->owner->drivers()->count();
        $data['total_supervisor']   = $this->owner->supervisors()->count();
        $data['total_coAdmin']      = $this->owner->coAdmins()->count();
        $data['total_counter']      = $this->owner->counters()->count();
        $data['total_cManager']     = $this->owner->counterManagers()->count();
        $data['total_route']        = $routes->count();
        $data['total_trip']         = $this->owner->trips()->count();
        $data['active_packages']    = $active_packages;

        return view('owner.dashboard', $data, compact('booked_ticket', 'monthly_sale'));
    }

    public function package()
    {
        $page_title     = 'Packages';
        $packages       = Package::where('status', 1)->orderBy('price')->get();
        $features       = Feature::latest()->get();
        $active_package = $this->owner->activePackages();
        $empty_message  = 'No Package Found';
        return view('owner.package.list', compact('page_title', 'packages', 'active_package', 'empty_message', 'features'));
    }

    public function packageActive()
    {
        $page_title     = 'Active Package';
        $packages       = $this->owner->activePackages()->sortBy('ends_at');
        $features       = Feature::latest()->get();
        $empty_message  = 'You don\'t have any active package';
        return view('owner.package.active', compact('page_title', 'packages', 'empty_message', 'features'));
    }

    public function packageBuy(Request $request)
    {
        $request->validate([
            'id' => 'required|gt:0'
        ]);

        $package    = Package::findOrFail($request->id);
        $start_from = Carbon::now();

        $old_package= $this->owner->boughtPackages()->sortByDesc('ends_at')->first();

        if($old_package){
            $start_from = Carbon::now();
            $start_from = Carbon::parse($old_package->ends_at);
        }

        $ends_at    = getPackageExpireDate($package->time_limit, $package->unit, $start_from);

        $soldPackage= $this->owner->soldPackages()->create([
            'package_id'    => $request->id,
            'starts_from'   => $start_from,
            'price'         => $package->price,
            'ends_at'       => $ends_at,
            'order_number'  => getTrx()
        ]);

        session()->put('order_number', $soldPackage->order_number);
        return redirect()->route('owner.deposit');
    }

    public function profile()
    {
        $page_title = 'Profile';
        $owner = $this->owner;
        return view('owner.profile', compact('page_title', 'owner'));
    }

    public function profileUpdate(Request $request)
    {
        $owner = $this->owner;

        $this->validate($request, [
            'owner_name'    => 'required|string|max:50',
            'username'      => 'required|alpha_dash|min:6|unique:owners,username,'.$owner->id,
            'email'         => 'required|string|email|max:90|unique:owners,email,'.$owner->id,
            'mobile'        => 'required|string|max:50|unique:owners,mobile,'.$owner->id,
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
                $old = $owner->image ?: null;
                $owner->image = uploadImage($request->image, 'assets/owner/images/profile/', '400X400', $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $owner->owner_name   = $request->owner_name;
        $owner->username     = $request->username;
        $owner->email        = $request->email;
        $owner->mobile       = $request->mobile;
        $owner->address      = $address;

        $owner->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('owner.profile')->withNotify($notify);
    }

    public function password()
    {
        $page_title = 'Password Setting';
        $owner = $this->owner;
        return view('owner.password', compact('page_title', 'owner'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password'  => 'required',
            'password'      => 'required|min:6|confirmed',
        ]);

        $user = $this->owner;
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('owner.password')->withNotify($notify);
    }

    public function depositHistory()
    {
        $page_title = 'Payment History';
        $empty_message = 'No history found.';
        $logs = auth()->guard('owner')->user()->deposits()->with(['gateway'])->latest()->paginate(getPaginate());
        return view('owner.payment.deposit_history', compact('page_title', 'empty_message', 'logs'));
    }

    public function generalSettings()
    {
        $page_title     = 'General Settings';
        $empty_message  = 'No history found.';
        $owner          = $this->owner;
        return view('owner.settings.general', compact('page_title', 'empty_message', 'owner'));
    }

    public function generalSettingsUpdate(Request $request)
    {

        $request->validate([
            'company_name'      => 'required|string|',
            'currency'          => 'required|string|',
            'currency_symbol'   => 'required|string|max:10',
            'logo'              => 'image|mimes:jpg,jpeg,png',
        ]);

        $owner = $this->owner;
        if ($request->hasFile('logo')) {
            try {
                $path = imagePath()['ownerLogo']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->logo)->save($path . "/$owner->username.png");
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Logo could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $owner->general_settings = $request->except('_token', 'logo');
        $owner->save();

        $notify[] = ['success', 'Genral Settings Updated Successfully.'];
        return redirect()->back()->withNotify($notify);

    }
}
