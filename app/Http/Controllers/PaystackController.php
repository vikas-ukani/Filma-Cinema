<?php

namespace App\Http\Controllers;

use App\Config;
use App\Package;
use Illuminate\Support\Facades\Auth;
use Paystack;

 
class PaystackController extends Controller
{
   
    public function paystackgateway()
    {
        return Paystack::getAuthorizationUrl()->redirectNow();
    }

    public function paystackcallback()
    {
        $auth = Auth::user();
        $payment = Paystack::getPaymentData();
        if ($payment['data']['status'] == 'success' && $payment['status'] == 'true') {
            $plan = Package::wherePlanId($payment['data']['metadata']['plan_id'])->first();
            $user_email = $auth->email;
            $com_email = Config::findOrFail(1)->w_email;

            $payment_id = $payment['data']['reference'];
            $payment_amount = $payment['data']['amount'];
            $payment_method = 'paystack';
            $payment_status = 1;
            $plan_id = $plan->id;
            $checkout = new SubscriptionController;
            return $checkout->subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount);

        } else {
            return redirect('/')->with('error', __('Payment error occured. Please try again !'));
        }
    }
}
