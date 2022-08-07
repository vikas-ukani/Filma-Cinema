<?php

namespace App\Http\Controllers;

use App\Config;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class PayViaCashFreeController extends Controller
{
  
    public function payment(Request $request)
    {
        $apiEndpoint = env('CASHFREE_API_END_URL');
        $opUrl = $apiEndpoint . "/api/v1/order/create";
        $orderid = uniqid();
        $cf_request = array();
        $cf_request["appId"] = env('CASHFREE_APP_ID');
        $cf_request["secretKey"] = env('CASHFREE_SECRET_ID');
        $cf_request["orderId"] = $orderid;
        $cf_request["orderAmount"] = $request->amount;
        $cf_request["orderNote"] = "Subscription";
        $cf_request["customerPhone"] = Auth::user()->mobile;
        $cf_request["customerName"] = Auth::user()->name;
        $cf_request["customerEmail"] = Auth::user()->email;
        $cf_request["returnUrl"] = '' . url("/cashfree/success") . '';
        $cf_request["notifyUrl"] = '' . url("/test/cf/notify") . '';

        $timeout = 20;

        $request_string = "";
        foreach ($cf_request as $key => $value) {
            $request_string .= $key . '=' . rawurlencode($value) . '&';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$opUrl?");
        curl_setopt($ch, CURLOPT_POST, count($cf_request));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $curl_result = curl_exec($ch);
        curl_close($ch);

        $jsonResponse = json_decode($curl_result);

        if ($jsonResponse->{'status'} == "OK") {
            $paymentLink = $jsonResponse->{"paymentLink"};
            Session::put('orderid', $orderid);
            Session::put('plan_id', $request->plan_id);
            return redirect($paymentLink);

        } else {
            return back()->with('deleted', $jsonResponse->{'reason'});

        }

    }
    public function success()
    {
        $user_email = Auth::user()->email;
        $com_email = Config::findOrFail(1)->w_email;

        Session::put('user_email', $user_email);
        Session::put('com_email', $com_email);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('CASHFREE_API_END_URL') . '/api/v1/order/info/status',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => 'appId=' . env("CASHFREE_APP_ID") . '&secretKey=' . env("CASHFREE_SECRET_ID") . '&orderId=' . \Session::get('orderid'),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            if (isset($response) && $response['status'] == 'OK') {

                // Do your thing ...

                $plan = Package::find(Session::get('plan_id'));

                $payment_id = $response['referenceId'];
                $payment_amount = $plan->amount;
                $payment_method = 'cashfree';
                $payment_status = $response['txStatus'] == 'SUCCESS' ? 1 : 0;
                $plan_id = $plan->id;
                $checkout = new SubscriptionController;
                return $checkout->subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount);

            } else {

                return back()->with('deleted', $response['txStatus']);
            }
        }
    }

}
