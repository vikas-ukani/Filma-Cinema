<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PayViaRazorpayController extends Controller
{
   

    public function success(Request $request, $planid)
    {

        $plan = Package::findorFail($planid);
        $input = $request->all();
        //get API Configuration
        $api = new Api(env('RAZOR_PAY_KEY'), env('RAZOR_PAY_SECRET'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (isset($payment) && $payment != null) {
            $payment_id = $payment->id;
            $payment_amount = $payment->amount / 100;
            $payment_method = 'razorpay';
            $payment_status = 1;
            $plan_id = $plan->id;
            $checkout = new SubscriptionController;
            return $checkout->subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount);
        } else {
            return redirect('/')->with('deleted', __('Payment failed'));
        }

    }

}
