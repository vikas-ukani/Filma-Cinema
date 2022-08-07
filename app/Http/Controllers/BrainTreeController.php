<?php

namespace App\Http\Controllers;

use App\Config;
use App\Menu;
use App\Multiplescreen;
use App\Package;
use App\PaypalSubscription;
use App\User;
use Braintree\MerchantAccount;
use Braintree_Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


class BrainTreeController extends Controller
{
   
    public function accesstoken()
    {
        $gateway = $this->brainConfig();
        $clientToken = $gateway->clientToken()->generate();
        return response()->json(array('client' => $clientToken));
    }

    public function payment(Request $request)
    {
        $gateway = $this->brainConfig();
        $customer = Auth::user();
        $currency = Config::findOrFail(1)->currency_code;
        $plan = Package::findOrFail($request->plan_id);

        $acc = $gateway->merchantAccount()->find(env('BTREE_MERCHANT_ACCOUNT_ID'));

        if (isset($acc) && ($acc->currencyIsoCode == $currency)) {
            $result = $gateway->transaction()->sale([
                'amount' => $request->amount,
                'paymentMethodNonce' => $request->payment_method_nonce,
                'customerId' => $this->get_bt(),
                'options' => [
                    'submitForSettlement' => true,
                ],
            ]);

            if ($result->success || !is_null($result->transaction)) {
                $txn = $result->transaction;
                if ($txn->paymentInstrumentType == 'paypal_account') {
                    $paypal = $txn->paypal;
                }

                $plan = Package::findOrFail($request->plan_id);
                $menus = Menu::all();
                $user_email = $customer->email;
                $com_email = Config::findOrFail(1)->w_email;
                $current_date = Carbon::now();
                $end_date = null;

                if ($plan->interval == 'month') {
                    $end_date = Carbon::now()->addMonths($plan->interval_count);
                } else if ($plan->interval == 'year') {
                    $end_date = Carbon::now()->addYears($plan->interval_count);
                } else if ($plan->interval == 'week') {
                    $end_date = Carbon::now()->addWeeks($plan->interval_count);
                } else if ($plan->interval == 'day') {
                    $end_date = Carbon::now()->addDays($plan->interval_count);
                }

                $created_subscription = PaypalSubscription::create([
                    'user_id' => $customer->id,
                    'payment_id' => isset($paypal) ? $paypal['paymentId'] : $txn->id,
                    'user_name' => $customer->name,
                    'package_id' => $request->plan_id,
                    'price' => $txn->amount,
                    'status' => '1',
                    'method' => isset($paypal) ? 'paypal' : 'braintree',
                    'subscription_from' => $current_date,
                    'subscription_to' => $end_date,
                ]);
                if ($created_subscription) {
                    if (isset($mlt_screen) && $mlt_screen == 1) {
                        $auth = Auth::user();
                        $screen = $plan->screens;
                        if ($screen > 0) {
                            $multiplescreen = Multiplescreen::where('user_id', $auth->id)->first();
                            if (isset($multiplescreen)) {
                                $multiplescreen->update([
                                    'pkg_id' => $plan->id,
                                    'user_id' => $auth->id,
                                    'screen1' => $screen >= 1 ? $auth->name : null,
                                    'screen2' => $screen >= 2 ? 'Screen2' : null,
                                    'screen3' => $screen >= 3 ? 'Screen3' : null,
                                    'screen4' => $screen >= 4 ? 'Screen4' : null,
                                ]);
                            } else {
                                $multiplescreen = Multiplescreen::create([
                                    'pkg_id' => $plan->id,
                                    'user_id' => $auth->id,
                                    'screen1' => $screen >= 1 ? $auth->name : null,
                                    'screen2' => $screen >= 2 ? 'Screen2' : null,
                                    'screen3' => $screen >= 3 ? 'Screen3' : null,
                                    'screen4' => $screen >= 4 ? 'Screen4' : null,
                                ]);
                            }
                        }
                    }

                    Session::forget('coupon_applied');
                    if (env('MAIL_DRIVER') != null && env('MAIL_HOST') != null && env('MAIL_PORT') != null) {
                        try {
                            Mail::send('user.invoice', ['paypal_sub' => $created_subscription, 'invoice' => null], function ($message) use ($com_email, $user_email) {
                                $message->from($com_email)->to($user_email)->subject('Invoice');
                            });
                        } catch (\Swift_TransportException $e) {
                            header("refresh:5;url=./");
                         
                        }
                    }
                }

                if (isset($menus) && count($menus) > 0) {
                    return redirect()->route('home', $menus[0]->slug)->with('added', __('Your are now a subscriber !'));
                }
                return redirect('/')->with('added', __('Your are now a subscriber !'));
            } else {
                return redirect('/')->with('error', __('Payment error occured. Please try again !'));
            }
        } else {
            return back()->with('deleted', __('Currency not supported !'));
        }
    }

    public function get_bt()
    {
        if (!Auth::user()->braintree_id) {
            $gateway = $this->brainConfig();
            $result = $gateway->customer()->create([
                'firstName' => Auth::user()->name,
                'email' => Auth::user()->email,
            ]);
            if ($result->success) {
                User::where('id', Auth::user()->id)->update(['braintree_id' => $result->customer->id]);
                return $result->customer->id;
            }
        } else {
            return Auth::user()->braintree_id;
        }
    }

    public function brainConfig()
    {
        return $gateway = new Braintree_Gateway([
            'environment' => env('BTREE_ENVIRONMENT'),
            'merchantId' => env('BTREE_MERCHANT_ID'),
            'publicKey' => env('BTREE_PUBLIC_KEY'),
            'privateKey' => env('BTREE_PRIVATE_KEY'),
        ]);
    }
}
