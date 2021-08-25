<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Package;
use App\SoldPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $page_title = 'All Packages';
        $packages = Package::paginate(getPaginate());

        $empty_message = 'No package found.';
        return view('admin.package.index', compact('page_title', 'packages', 'empty_message'));
    }

    public function soldPackage()
    {
        $page_title = 'Sold Packages';
        $packages   = SoldPackage::where('status', '!=', 0)->with('package')->paginate(getPaginate());

        $empty_message = 'No package found.';
        return view('admin.package.sold', compact('page_title', 'packages', 'empty_message'));
    }

    public function store(Request $request, $id)
    {
        $validation_rule = [
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|',
            'time_limit'    => 'required|numeric',
            'unit'          => 'required|string',
        ];

        $request->validate($validation_rule);

        if($id==0){
            $package = new Package();
            $notify[] = ['success', 'New Package Added Successfully'];
        }else{
            $notify[] = ['success', 'Package Updated Successfully'];
            $package = Package::find($id);
        }

        $package->name          = $request->name;
        $package->price         = $request->price;
        $package->time_limit    = $request->time_limit;
        $package->unit          = $request->unit;
        $package->status        = $request->status?true:false;

        $package->save();

        return redirect()->back()->withNotify($notify);

    }
}
