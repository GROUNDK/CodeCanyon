<?php

namespace App\Http\Controllers\Admin;

use App\Frontend;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class FrontendController extends Controller
{

    public function templates()
    {
        $page_title = 'Templates';
        $temPaths = array_filter(glob('core/resources/views/templates/*'), 'is_dir');
        $i = 0;
        foreach ($temPaths as $temp) {
            $arr = explode('/', $temp);
            $tempname = end($arr);
            $templates[$i]['name'] = $tempname;
            $templates[$i]['image'] = asset($temp) . '/preview.jpg';
            $i++;
        }
        $extra_templates = json_decode(getTemplates(), true);
        return view('admin.frontend.templates', compact('page_title', 'templates', 'extra_templates'));

    }

    public function templatesActive(Request $request)
    {
        $general = GeneralSetting::first();

        $general->active_template=  $request->name;
        $general->save();

        $notify[] = ['success', 'Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function seoEdit()
    {
        $page_title = 'SEO Configuration';
        $seo = Frontend::where('data_keys', 'seo.data')->first();
        if(!$seo){
            $data_values = '{"keywords":["admin","blog"],"description":"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit","social_title":"Viserlab","social_description":"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit","image":null}';
            $data_values = json_decode($data_values, true);
            Frontend::create([
               'data_keys'=> 'seo.data',
               'data_values'=> $data_values,
            ]);
        }
        return view('admin.frontend.seo', compact('page_title', 'seo'));
    }

    public function frontendSections($key)
    {
        $section    = getPageSections()->$key;
        $content    = Frontend::where('data_keys', $key . '.content')->latest()->first();
        $elements   = Frontend::where('data_keys', $key . '.element')->latest()->get();

        $page_title = $section->name ;
        $empty_message = 'No item create yet.';
        return view('admin.frontend.index', compact('section', 'content', 'elements', 'key', 'page_title', 'empty_message'));
    }


    public function frontendContent(Request $request, $key)
    {
        $purifier           = new \HTMLPurifier();
        $validation_rule    = [];
        $valInputs          = $request->except('_token', 'image_input', 'key', 'status', 'type', 'id');


        foreach ($valInputs as $keyName => $input) {

            if (gettype($input) == 'array') {
                $inputContentValue[$keyName] = $input;
                continue;
            }

            $inputContentValue[$keyName] = $purifier->purify($input);
        }

        foreach ($request->except('_token', 'video') as $input_field => $val) {

            if ($input_field == 'image_input' && !isset($validation_rule['image_input'])) {
                $validation_rule['image_input'] = ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])];
                continue;
            }


            $validation_rule[$input_field] = 'required';
        }

        $request->validate($validation_rule, [], ['image_input' => 'image']);

        if ($request->id) {
            $content = Frontend::findOrFail($request->id);
        } else {
            $content = Frontend::where('data_keys', $key . '.' . $request->type)->first();
            if (!$content || $request->type == 'element') {
                $content = Frontend::create(['data_keys' => $key . '.' . $request->type]);
            }
        }

        if ($request->hasFile('image_input')) {
            try {
                $inputContentValue['image'] = $this->storeImage($key, $request->type, $request->image_input, @$content->data_values->image);
            } catch (\Exception $exp) {

                $notify[] = ['error', 'Could not upload the Image.'];
                return back()->withNotify($notify);
            }
        } else if (isset($content->data_values->image)) {
            $inputContentValue['image'] = $content->data_values->image;
        }
        $content->update(['data_values' => $inputContentValue]);
        $notify[] = ['success', 'Content has been updated.'];
        return back()->withNotify($notify);
    }


    public function frontendElement($key, $id = null)
    {
        $section = getPageSections()->$key;
        unset($section->element->modal);
        $page_title = $section->name . ' Items';
        if ($id) {
            $data = Frontend::findOrFail($id);
            return view('admin.frontend.element', compact('section', 'key', 'page_title', 'data'));
        }
        return view('admin.frontend.element', compact('section', 'key', 'page_title'));
    }

    protected function storeImage($key, $type, $image, $old_image = null)
    {

        $path = 'assets/images/frontend/' . $key;
        if ($type == 'element') {
            $section = getPageSections()->$key;
            $size = @$section->element->image_property->size;
            $thumb = @$section->element->image_property->thumb;
        } elseif ($type == 'content') {
            $section = getPageSections()->$key;
            $size = @$section->content->image_property->size;
            $thumb = @$section->content->image_property->thumb;
        } else {
            $path = imagePath()[$key]['path'];
            $size = imagePath()[$key]['size'];
            $thumb = @imagePath()[$key]['thumb'];

        }
        return uploadImage($image, $path, $size, $old_image, $thumb);
    }

    public function remove(Request $request)
    {
        $request->validate(['id' => 'required']);
        $frontend = Frontend::findOrFail($request->id);
        if (isset($frontend->value->image)) {
            removeFile( 'assets/images/frontend/'.$frontend->key. '/' . $frontend->value->image);
        }
        $frontend->delete();
        $notify[] = ['success', 'Content has been removed.'];
        return back()->withNotify($notify);
    }
}
