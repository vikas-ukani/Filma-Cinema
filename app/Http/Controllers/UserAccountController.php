<?php

namespace App\Http\Controllers;

use App\Affilate;
use App\Config;
use App\Mail\SendInvoiceMailable;
use App\ManualPaymentMethod;
use App\Menu;
use App\Package;
use App\PackageFeature;
use App\PaypalSubscription;
use App\PricingText;
use App\User;
use App\Country;
use App\State;
use App\City;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Cashier;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Stripe\Subscription;
use \Stripe\Coupon;
use \Stripe\Invoice;
use \Stripe\Stripe;

class UserAccountController extends Controller
{


    public function index()
    {
        // Set your secret key: remember to change this to your live secret key in production
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $auth = Auth::user();
        if ($auth->stripe_id != null) {

            $customer = Cashier::findBillable($auth->stripe_id);
        }
        $paypal = $auth->paypal_subscriptions->sortBy('created_at');
        $plans = Package::all();
        $country = Country::all();
        $state= State::all();
        $city = City::all();
        $current_subscription = null;
        $method = null;
        $current_date = Carbon::now()->toDateString();
        if (isset($customer)) {

            $alldata = $auth->subscriptions;
            $data = $alldata->last();
        }
        if (isset($paypal) && $paypal != null && count($paypal) > 0) {
            $last = $paypal->last();
        }
        $stripedate = isset($data) ? $data->created_at : null;
        $paydate = isset($last) ? $last->created_at : null;
        if ($stripedate > $paydate) {
            if ($auth->subscribed($data->name)) {
                if (date($current_date) <= date($data->subscription_to)) {
                    $current_subscription = $data;
                    $method = 'stripe';
                }
            }
        } elseif ($stripedate < $paydate) {
            if (date($current_date) <= date($last->subscription_to)) {
                $current_subscription = $last;
                $method = 'paypal';
            }
        }

        $paypal_subscriptions = PaypalSubscription::where('user_id', $auth->id)->get();
        $customer = Cashier::findBillable($auth->stripe_id);

        if ($customer) {
            $invoices = $customer->subscriptions;
        } else {
            $invoices = null;
        }

        $currency_symbol = Config::first()->currency_symbol;
        

        //affiliate
        $af_settings = Affilate::first();

        if (!$af_settings || $af_settings->enable_affilate != 1) {
            abort(404);
        }

        if (auth()->user()->refer_code == '') {

            auth()->user()->update([
                'refer_code' => User::createReferCode(),
            ]);

        }

        $aff_history = auth()->user()->getReferals()->with(['user' => function ($q) {
            return $q->select('id', 'email');
        }])->wherehas('user')->paginate(10);

        $earning = auth()->user()->getReferals()->wherehas('user')->sum('amount');

       

        return view('user.index', compact('auth', 'plans', 'country', 'state', 'city', 'current_subscription', 'method', 'invoices', 'paypal_subscriptions', 'currency_symbol','earning','aff_history','af_settings'));
    }

    public function purchase_plan()
    {
        $plans = Package::all();
        $pricingTexts = PricingText::all();
        $package_feature = PackageFeature::get();

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $auth = Auth::user();
        if ($auth->stripe_id != null) {
            $customer = Cashier::findBillable($auth->stripe_id);
        }
        $paypal = $auth->paypal_subscriptions->sortBy('created_at');
        $plans = Package::all();
        $current_subscription = null;
        $method = null;
        $current_date = Carbon::now()->toDateString();
        if (isset($customer)) {

            $alldata = $auth->subscriptions;
            $data = $alldata->last();
        }
        if (isset($paypal) && $paypal != null && count($paypal) > 0) {
            $last = $paypal->last();
        }
        $stripedate = isset($data) ? $data->created_at : null;
        $paydate = isset($last) ? $last->created_at : null;
        if ($stripedate > $paydate) {
            if ($auth->subscribed($data->name)) {
                if (date($current_date) <= date($data->subscription_to)) {
                    $current_subscription = $data;
                }
            }
        } elseif ($stripedate < $paydate) {
            if (date($current_date) <= date($last->subscription_to)) {
                $current_subscription = $last;
            }
        }

       
        return view('user.purchaseplan', compact('plans', 'pricingTexts', 'package_feature', 'current_subscription'));
    }

    public function get_payment(Request $request, $id)
    {
        $plan = Package::findOrFail($id);
        $config = Config::first();
        if (!isset($config) && $config == null) {
            return back()->with('deleted', __('Default Settings not found !'));
        }
        $bankdetails = $config->bankdetails;
        $razorpay_payment = $config->razorpay_payment;
        $instamojo_payment = $config->instamojo_payment;
        $stripe_payment = $config->stripe_payment;
        $mollie_payment = $config->mollie_payment;
        $coin_payment = $config->coinpay;
        $cashfree_payment = $config->cashfree_payment;
        $omise_payment = $config->omise_payment;
        $flutterrave_payment = $config->flutterrave_payment;
        $payhere_payment = $config->payhere_payment;
        $account_name = $config->account_name;
        $account_no = $config->account_no;
        $ifsc_code = $config->ifsc_code;
        $bank = $config->bank_name;
        $manualpaymentmethod = ManualPaymentMethod::where('status', 1)->get();
        $intent = '';
        if (env('STRIPE_SECRET') != null && env('STRIPE_KEY') != null && $stripe_payment == 1) {
            $paymentMethods = $request->user()->paymentMethods();

            $intent = $request->user()->createSetupIntent();
        }

        $currency = DB::table('currencies')->where('code', $config->currency_code)->first();
        if (isset($currency) && $currency != null) {
            $currency_payments = $currency->payment_method;
            $currency_payments = (array) json_decode($currency_payments, true);
        }
        return view('subscribe', compact('plan','stripe_payment', 'currency_payments', 'bankdetails', 'account_no', 'account_name', 'ifsc_code', 'bank', 'razorpay_payment', 'intent', 'instamojo_payment', 'mollie_payment', 'cashfree_payment', 'omise_payment', 'flutterrave_payment', 'manualpaymentmethod', 'coin_payment', 'payhere_payment'));
    }

    public function subscribe(Request $request)
    {

        $menus = Menu::all();
        ini_set('max_execution_time', 80);
        // Set your secret key: remember to change this to your live secret key in production
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $auth = Auth::user();
        $token = $request->stripeToken;
        $coupon_valid = false;
        $coupon = $request->coupon;
        $plan = Package::find($request->plan);
        $paymentMethod = $request->paymentMethod;

        if (!$plan) {
            return back()->with('delete', __('Plan not found !'));

        }

        if ($coupon != null) {
            try
            {
                $stripe_coupon = Coupon::retrieve($coupon);
                $coupon_valid = true;
                if ($stripe_coupon->valid == false) {
                    $coupon_valid = false;
                    return back()->with('deleted', __('Coupon has been expired'));
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
                $coupon_valid = false;
            }
        }

        if ($coupon_valid == false && $request->coupon) {
            return back()->with('deleted', $error);
        }

        $plan_id = $plan->plan_id;
        $plan_name = $plan->name;

        if ($coupon_valid == true && $request->coupon) {
            try {
                $purchased = $auth->newSubscription($plan_name, $plan_id)->withCoupon($request->coupon)->create($paymentMethod, [
                    'email' => $auth->email,
                ]);

                $last_plan = $auth->subscriptions->last();
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

                DB::table('subscriptions')->where('id', '=', $last_plan->id)->update([
                    'subscription_from' => $current_date,
                    'subscription_to' => $end_date,
                    'amount' => $plan->amount,
                ]);

                if (isset($purchased) || $purchased != null) {
                    Mail::to($auth->email)->send(new SendInvoiceMailable());
                    if (isset($menus) && count($menus) > 0) {
                        return redirect()->route('home', $menus[0]->slug)->with('added', __('Your are now a subscriber !'));
                    }
                    return redirect('/')->with('added', __('Your are now a subscriber !'));
                } else {
                    return back()->with('deleted', __('Subscription failed ! Please check your debit or credit card.'));
                }
            } catch (\Exception $e) {
                return back()->with('deleted', $e->getMessage());
            }

        } else {

            try {
                $purchased = $auth->newSubscription($plan_name, $plan_id)
                    ->create($paymentMethod, [
                        'email' => $auth->email,
                    ]);

                $last_plan = $auth->subscriptions->last();
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

                DB::table('subscriptions')->where('id', '=', $last_plan->id)->update([
                    'subscription_from' => $current_date,
                    'subscription_to' => $end_date,
                    'amount' => $plan->amount,
                ]);

                if (isset($purchased) || $purchased != null) {
                    Mail::to($auth->email)->send(new SendInvoiceMailable());
                    if (isset($menus) && count($menus) > 0) {
                        return redirect()->route('home', $menus[0]->slug)->with('added', __('Your are now a subscriber !'));
                    }
                    return redirect('/')->with('added', __('Your are now a subscriber !'));
                } else {
                    return back()->with('deleted', __('Subscription failed ! Please check your debit or credit card.'));
                }

            } catch (\Exception $e) {
                return back()->with('deleted', $e->getMessage());
            }
        }
    }

    public function edit_profile()
    {
        return view('user.edit_profile');
    }

    public function update_profile(Request $request)
    {

        $auth = Auth::user();

        if ($request->image != null) {

            if ($file = $request->file('image')) {
                $name = 'user_' . time() . $file->getClientOriginalName();
                if ($auth->image != null) {
                    $content = @file_get_contents(public_path() . '/images/users/' . $auth->image);
                    if ($content) {
                        unlink(public_path() . '/images/users/' . $auth->image);
                    }
                }
                $file->move('images/users/', $name);
                $input['image'] = $name;
                $auth->update([
                    'image' => $input['image'],
                ]);
                return back()->with('updated', __('Profile image has been updated'));

            }
        }

        if (Hash::check($request->current_password, $auth->password)) {
            if ($request->new_email !== null) {
                $request->validate([
                    'new_email' => 'required|email',
                    'current_password' => 'required',
                ]);
                $auth->update([
                    'email' => $request->new_email,
                ]);
                return back()->with('updated', __('Email has been updated'));
            }

            if ($request->new_name !== null) {
                $request->validate([
                    'new_name' => 'required|',
                    'current_password' => 'required',
                ]);
                $auth->update([
                    'name' => $request->new_name,
                ]);
                return back()->with('updated', __('Name has been updated'));
            }

            if ($request->new_password !== null) {
                $request->validate([
                    'new_password' => 'required|min:6',
                    'current_password' => 'required',
                ]);
                $auth->update([
                    'password' => bcrypt($request->new_password),
                ]);
                return back()->with('updated', __('Password has been updated'));
            }

        }

    }


    public function update_otherprofilesetting(Request $request){
        $auth = Auth::user();
        $auth->update([
            'facebook_url' => isset($request->facebook_url)  ?  $request->facebook_url : NULL,
            'youtube_url' =>  isset($request->youtube_url)  ?  $request->youtube_url : NULL,
            'twitter_url' =>  isset($request->twitter_url)  ?  $request->twitter_url : NULL
        ]);
        return back()->with('updated', __('User other settings has been updated'));
        

    }

    public function history()
    {
        $auth = Auth::user();
        // Set your secret key: remember to change this to your live secret key in production
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paypal_subscriptions = PaypalSubscription::where('user_id', $auth->id)->get();
        $customer = Cashier::findBillable($auth->stripe_id);

        $invoices = $auth->subscriptions;
        return view('user.history', compact('invoices', 'paypal_subscriptions'));
    }
    public function cancelSub($plan_id)
    {

        try {
            $subs = auth()->user()->subscriptions()->orderBY('id', 'DESC')->first();

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            \Stripe\Subscription::update(
                $subs->stripe_id,
                [
                    'pause_collection' => [
                        'behavior' => 'mark_uncollectible',
                    ],
                ]
            );

        } catch (\Exception $e) {

        }

        return back()->with('deleted', __('Subscription has been cancelled'));
    }

    public function resumeSub($plan_id)
    {

        $subs = auth()->user()->subscriptions()->orderBY('id', 'DESC')->first();

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        \Stripe\Subscription::update(
            $subs->stripe_id,
            [
                'pause_collection' => '',
            ]
        );

        return back()->with('updated', __('Subscription has been resumed'));
    }

    public function PaypalCancel()
    {
        $auth = Auth::user();
        $auth->paypal_subscriptions->last()->status = 0;
        $auth->paypal_subscriptions->last()->save();
        return back()->with('deleted', __('Subscription has been cancelled'));
    }

    public function PaypalResume()
    {
        $auth = Auth::user();
        $last = $auth->paypal_subscriptions->last();
        $last->status = 1;
        $last->save();
        return back()->with('updated', __('Subscription has been resumed'));
    }
    public function watchhistory()
    {
        return view('search');
    }

    public function invoice($id)
    {
        $invoice = PaypalSubscription::find($id);
        if (selected_lang()->rtl == 0) {
            return view('user.show_invoice', compact('invoice'));
        } else {
            return view('user.show_invoice_rtl', compact('invoice'));
        }

    }

    public function pdfdownload($id)
    {
        $stylesheet = file_get_contents('css/bootstrap.min.css');
        $invoice = PaypalSubscription::find($id);
        if (selected_lang()->rtl == 0) {
            $pdf = Pdf::loadView('user.download', compact('invoice'), [],
                [
                    'title' => 'Invoice',
                    'orientation' => 'L',
                    'images' => true,
                ]
            );
        } else {
            $pdf = Pdf::loadView('user.download-rtl', compact('invoice'), [],
                [
                    'title' => 'Invoice',
                    'orientation' => 'L',
                    'images' => true,
                ]
            );
        }

        return $pdf->download('invoice.pdf');
    }
}
