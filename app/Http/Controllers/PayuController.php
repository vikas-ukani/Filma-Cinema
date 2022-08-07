<?php

namespace App\Http\Controllers;

use App\Config;
use App\Menu;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tzsk\Payu\Facade\Payment;

  /*==========================================
    =            Author: Media City            =
    Author URI: https://mediacity.co.in
    =            Author: Media City            =
    =            Copyright (c) 2022            =
    ==========================================*/
class PayuController extends Controller
{
  
    public function payment(Request $request)
    {
        $plan = Package::findOrFail($request->plan_id);
        $currency_code = Config::first()->currency_code;
        $auth = Auth::user();

        if (Session::has('coupon_applied')) {
            $amount = $plan->amount - Session::get('coupon_applied')['amount'];
        } else {
            $amount = $plan->amount;
        }

        if ($currency_code != 'INR') {
            return back()->with('deleted', 'Currency is in ' . strtoupper($currency_code) . ' so payumoney only support INR currency.');
        }

        $attributes = [
            'txnid' => strtoupper(str_random(8)), # Transaction ID.
            'amount' => $amount, # Amount to be charged.
            'productinfo' => $plan->name,
            'firstname' => $auth->name, # Payee Name.
            'email' => $auth->email, # Payee Email Address.
            'phone' => '1234567890', # Payee Phone Number.
        ];

        Session::put('plan', $plan);
        return Payment::make($attributes, function ($then) {
            $then->redirectTo('payment/status');
        });
    }

    public function status()
    {
        $payment = Payment::capture();
        $menus = Menu::all();
        $plan = Session::get('plan');
        $user_email = Auth::user()->email;
        $com_email = Config::findOrFail(1)->w_email;

        Session::put('user_email', $user_email);
        Session::put('com_email', $com_email);

        Session::forget('plan');

        $session_amount = session()->has('coupon_applied') ? session()->get('coupon_applied')['amount'] : 0;

        // Get the payment status.
        $payment->isCaptured(); # Returns boolean - true / false

        if ($payment->isCaptured() == true) {

            $payment_id = $payment->txnid;
            $payment_amount = $plan->amount - $session_amount;
            $payment_method = 'payumoney';
            $payment_status = 1;
            $plan_id = $plan->id;
            $checkout = new SubscriptionController;
            return $checkout->subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount);
        } else {
            return redirect('/')->with('deleted', __('Payment not done due to some payumoney server issue !'));

        }

    }
}
