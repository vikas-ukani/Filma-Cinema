<?php

namespace App\Http\Controllers;

use App\Config;
use App\Menu;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mollie\Laravel\Facades\Mollie;

 
class PayViaMollieController extends Controller
{
  
    public function payment(Request $request)
    {
        $p = json_decode($request->metadata, true);
        $plan = Package::find($p['plan_id']);

        $amount = sprintf("%.2f", $request->amount);
        $payment = Mollie::api()->payments()->create([
            "amount" => [
                "currency" => $request->currency,
                "value" => $amount, // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            "description" => "Payment For " . $plan->name,
            "redirectUrl" => route('moli.pay.success_subscription'),
        ]);
        Session::put('plan', $plan);
        $payment = Mollie::api()->payments()->get($payment->id);
        Session::put('payment_id', $payment->id);
        // redirect customer to Mollie checkout page
        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function success(Request $request)
    {
        $payment_id = Session::get('payment_id');
        $payment = Mollie::api()->payments()->get($payment_id);

        $menus = Menu::all();
        $plan = Session::get('plan');
        $user_email = Auth::user()->email;
        $com_email = Config::findOrFail(1)->w_email;

        Session::put('user_email', $user_email);
        Session::put('com_email', $com_email);

        Session::forget('plan');

        if ($payment->isPaid()) {

        
            // Do your thing ...

            $payment_id = $payment->id;
            $payment_amount = $plan->amount;
            $payment_method = 'mollie';
            $payment_status = 1;
            $plan_id = $plan->id;
            $checkout = new SubscriptionController;
            return $checkout->subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount);

        } else {

            return back()->with('deleted', __('Fail Transcation !'));
        }

    }
}
