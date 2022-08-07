<?php

namespace App\Http\Controllers;

use App\Config;
use App\Mail\Contactus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class ContactController extends Controller
{
    public function contact()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|regex:/^.+@.+$/i',
            'subj' => 'required',
            'msg' => 'required',
        ],
            [
                'name.required' => __('Name cannot be empty'),
                'email.required' => __('Email cannot be empty'),
                'subj.required' => __('Please choose a subject'),
                'msg.required' => __('Message caanot be empty'),
            ]);

        $contact = array(
            'name' => $request->name,
            'email' => $request->email,
            'subj' => $request->subj,
            'msg' => $request->msg,
        );

        $defaultemail = Config::find(1)->w_email;

       try{
        if(env('MAIL_FROM_ADDRESS') != NULL && env('MAIL_HOST') != NULL && env('MAIL_USERNAME') != NULL && env('MAIL_PASSWORD') != NULL && env('MAIL_DRIVER') != NULL){
            Mail::to($defaultemail)->send(new Contactus($contact));
            return back()->with('success', __('Sent Succesfully, Thanks for contacting us!'));
        }
       }catch(\Exception $e){
         return back()->with('danger',$e->getMessage());
       }
       

        
    }
}
