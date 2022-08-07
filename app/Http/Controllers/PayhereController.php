<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

  
class PayhereController extends Controller
{
   
    public function callback(Request $request)
    {

        $authcode = base64_encode(env('PAYHERE_BUISNESS_APP_CODE') . ':' . env('PAYHERE_APP_SECRET'));

        if (env('PAYHERE_MODE') == 'sandbox') {
            $tokenurl = 'https://sandbox.payhere.lk/merchant/v1/oauth/token';
        } else {
            $tokenurl = 'https://www.payhere.lk/merchant/v1/oauth/token';
        }

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . $authcode,
        ])->post($tokenurl, [
            'grant_type' => 'client_credentials',
        ]);

        if ($response->successful()) {

            $result = $response->json();
            $accessToken = $result['access_token'];

            if (env('PAYHERE_MODE') == 'sandbox') {
                $orderurl = 'https://sandbox.payhere.lk/merchant/v1/payment/search?order_id=';
            } else {
                $orderurl = 'https://www.payhere.lk/merchant/v1/payment/search?order_id=';
            }

            $paymentStatus = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get($orderurl . $request->order_id);

            $status = $paymentStatus->json();

            if ($status['data'] == null) {
                return back()->with('deleted',__('Payment Failed ! Try Again'));

            } else {

                $txnid = $status['data'][0]['payment_id'];
                $plan_id = $request->order_id;
                $payment_status = '1';
                $payment_id = $txnid;
                $payment_amount = $request->amount;
                $payment_method = 'payhere';

                $checkout = new SubscriptionController;
                return $checkout->subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount);

            }

        } else {

            return back()->with('deleted', $response['msg']);
        }

    }
}
