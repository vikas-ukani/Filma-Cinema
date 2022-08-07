<?php

namespace App\Http\Controllers;

use App\Config;
use App\Menu;
use App\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use KingFlamez\Rave\Facades\Rave as Flutterwave;


class FlutterwaveController extends Controller
{
 
    public function initialize(Request $request)
    {

        //This initializes payment and redirects to the payment gateway
        //The initialize method takes the parameter of the redirect URL
        //Flutterwave::initialize(route('flutterrave.callback'));

        //This generates a payment reference
        $reference = Flutterwave::generateReference();
        // dd($reference);
        $plan = Package::find($request->plan_id);
        Session::put('plan', $plan);
        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => (request()->amount),
            'email' => request()->email,
            'tx_ref' => $reference,
            'currency' => "NGN",

            'redirect_url' => route('flutterrave.callback'),
            'customer' => [
                'email' => request()->email,
                "phone_number" => request()->phone,
                "name" => request()->name,
            ],

            "customizations" => [
                "title" => __('Subscription'),
                "description" => "22th May",
            ],
        ];

        $payment = Flutterwave::initializePayment($data);

        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return;
        }

        return redirect($payment['data']['link']);

    }

    public function callback()
    {

        $status = request()->status;

        //if payment is successful
        if ($status == 'successful') {

            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

          
            $current_date = Carbon::now();
            $com_email = Config::findOrFail(1)->w_email;
            $plan = Session::get('plan');
            $customer = Auth::user();
            $user_email = $customer->email;
            $menus = Menu::all();

            $payment_id = $data['data']['tx_ref'];
            $payment_amount = $data['data']['amount'];
            $payment_method = 'flutterwave';
            $payment_status = 1;
            $plan_id = $plan->id;
            $checkout = new SubscriptionController;
            return $checkout->subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount);

        } elseif ($status == 'cancelled') {
            //Put desired action/code after transaction has been cancelled here
            return redirect('account/purchaseplan');
        } else {
            return redirect('account/purchaseplan');
            //Put desired action/code after transaction has failed here
        }
    }
}
