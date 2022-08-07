<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;


class PayViaInstamojoController extends Controller
{
   
    public function __construct()
    {

        $this->api = new \Instamojo\Instamojo(
            config('services.instamojo.api_key'),
            config('services.instamojo.auth_token'),
            config('services.instamojo.url')
        );
    }
    public function pay(Request $request)
    {

        $plan = Package::find($request->plan_id);
        Cookie::queue('plan', $plan, 10);
        Session::put('plan', $plan);

        if (!isset($plan) && $plan == null) {
            return back()->with('deleted', 'Plan Not Found !');
        }
        try {

            $response = $this->api->paymentRequestCreate(array(
                "purpose" => "Membership Plan for " . $plan->name,
                "amount" => $request->amount,
                "buyer_name" => $request->name,
                "send_email" => true,
                "email" => $request->email,
                "phone" => $request->mobile,
                "redirect_url" => url('/instamojo/pay-success'),
            ));

            header('Location: ' . $response['longurl']);
            exit();
        } catch (\Exception $e) {
            print('Error: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        try {

            $plan = Session::get('plan');

            $api = new \Instamojo\Instamojo(
                config('services.instamojo.api_key'),
                config('services.instamojo.auth_token'),
                config('services.instamojo.url')
            );

            $response = $api->paymentRequestStatus(request('payment_request_id'));

            if (!isset($response['payments'][0]['status'])) {
                return back()->with('deleted', 'Payment failed !');
            } else if ($response['payments'][0]['status'] != 'Credit') {
                return back()->with('deleted', 'Payment failed !');
            } else {

                $payment_id = $response['payments'][0]['payment_id'];
                $payment_amount = $response['payments'][0]['amount'];
                $payment_method = 'instamojo';
                $payment_status = 1;
                $plan_id = $plan->id;
                $checkout = new SubscriptionController;
                return $checkout->subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount);
            }
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }
    }
}
