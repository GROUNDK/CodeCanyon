<?php

namespace App\Http\Controllers\Admin;

use App\Feature;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $page_title = 'All Features';
        $features = Feature::latest()->paginate(getPaginate());

        $empty_message = 'No feature found.';
        return view('admin.feature.index', compact('page_title', 'features', 'empty_message'));
    }

    public function store(Request $request, $id)
    {
        $validation_rule = [
            'name'          => 'required|string|max:255',
        ];

        $request->validate($validation_rule);

        if($id==0){
            $feature    = new Feature();
            $notify[]   = ['success', 'New Feature Added Successfully'];
        }else{
            $notify[]   = ['success', 'Feature Updated Successfully'];
            $feature    = Feature::find($id);
        }

        $feature->name  = $request->name;
        $feature->save();

        if($feature){
            return redirect()->back()->withNotify($notify);
        }else{
            abort(500);
        }
    }

    public function remove(Feature $feature)
    {
        $feature->delete();
        $notify[]=['success','Feature Deleted Successfully'];
        return back()->withNotify($notify);
    }
}
