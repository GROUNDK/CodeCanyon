<?php

namespace App\Http\Controllers;

use App\Feature;
use App\Frontend;
use App\Language;
use App\Package;
use App\SupportAttachment;
use App\SupportMessage;
use App\SupportTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;


class SiteController extends Controller
{
    public function index(){
        $data['page_title']         = 'Home';
        $data['packages']           = Package::whereStatus(1)->orderBy('price')->get();
        $data['plan_features']      = Feature::latest()->get();
        return view(activeTemplate() . 'home', $data);
    }

    public function contact()
    {
        $data['page_title'] = "Contact Us";
        return view(activeTemplate() . 'contact', $data);
    }


    public function contactSubmit(Request $request)
    {
        $ticket = new SupportTicket();
        $message = new SupportMessage();

        $imgs = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');

        $this->validate($request, [
            'attachments' => [
                'sometimes',
                'max:4096',
                function ($attribute, $value, $fail) use ($imgs, $allowedExts) {
                    foreach ($imgs as $img) {
                        $ext = strtolower($img->getClientOriginalExtension());
                        if (($img->getSize() / 1000000) > 2) {
                            return $fail("Images MAX  2MB ALLOW!");
                        }
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, pdf images are allowed");
                        }
                    }
                    if (count($imgs) > 5) {
                        return $fail("Maximum 5 images can be uploaded");
                    }
                },
            ],
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);


        $random = getNumber();

        $ticket->owner_id = auth()->id();
        $ticket->name = $request->name;
        $ticket->email = $request->email;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $path = imagePath()['ticket']['path'];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $image) {
                try {
                    SupportAttachment::create([
                        'support_message_id' => $message->id,
                        'image' => uploadImage($image, $path),
                    ]);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload your ' . $image];
                    return back()->withNotify($notify)->withInput();
                }

            }
        }
        $notify[] = ['success', 'ticket created successfully!'];

        return redirect()->route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return redirect()->back();
    }


    public function links($slug)
    {
        $array  = explode('-', $slug);
        $id     = end($array);
        $page   = Frontend::where('id', $id)->first();

        $data['page_title']     = htmlspecialchars_decode($page->data_values->page_title);
        $data['description']    = $page->data_values->description;

        return view(activeTemplate() . 'links', $data);
    }
}
