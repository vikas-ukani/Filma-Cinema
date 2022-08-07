<?php

namespace App\Http\Controllers;

use App\Button;
use App\ManualPayment;
use App\Menu;
use App\Multiplescreen;
use App\Package;
use App\PaypalSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class ManualPaymentController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manual_payment = ManualPayment::orderby('id', 'desc')->get();

        return view('admin.manual_payment.index', compact('manual_payment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $planid)
    {
       
        $menus = Menu::all();
        $plan = Package::findorFail($planid);

        if ($file = $request->file('recipt')) {
            $name = "manual_" . time() . $file->getClientOriginalName();
            $file->move('images/recipt', $name);
            $recipt_name = $name;

        }

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

        $auth = Auth::user();

        $created_subscription = ManualPayment::create([
            'user_id' => $auth->id,
            'payment_id' => strtoupper(str_random(8)),
            'user_name' => $auth->name,
            'package_id' => $plan->id,
            'price' => $plan->amount,
            'status' => 0,
            'file' => $recipt_name,
            'method' => $request->methpd,
            'subscription_from' => $current_date,
            'subscription_to' => $end_date,
        ]);

        if ($created_subscription) {

            Session::forget('coupon_applied');

            if (isset($menus) && count($menus) > 0) {
                return redirect()->route('home', $menus[0]->slug)->with('added', __('ManualPayment Recipt has been successfully Added !'));
            } else {
                return redirect('/')->with('added', __('ManualPayment Recipt has been successfully Added !'));
            }

        }
    }

    public function changemanualpayment($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $manual_payment = ManualPayment::findorfail($id);

        if ($manual_payment->status == 1) {
            $manual_payment->status = 0;
            $manual_payment->save();
        } else {

            $manual_payment->status = 1;

            $menus = Menu::all();
            $plan = Package::find($manual_payment->package_id);

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
                'user_id' => $manual_payment->user_id,
                'payment_id' => strtoupper(str_random(8)),
                'user_name' => $manual_payment->user_name,
                'package_id' => $plan->id,
                'price' => $plan->amount,
                'status' => 1,
                'method' => $manual_payment->method,
                'subscription_from' => $current_date,
                'subscription_to' => $end_date,
            ]);

            if (isset($created_subscription)) {
                $multi_screen = Button::first()->multiplescreen;
                if (isset($multi_screen) && $multi_screen == 1) {
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
            }

            $manual_payment->save();

        }
        return back()->with('added',__('Status change successsfully!'));

    }

    public function freePackageSubscription(Request $request, $planid)
    {
        $plan = Package::find($planid);
        $menus = Menu::all();
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
            'user_id' => Auth::user()->id,
            'payment_id' => strtoupper(str_random(8)),
            'user_name' => Auth::user()->name,
            'package_id' => $plan->id,
            'price' => $plan->amount,
            'status' => 1,
            'method' => 'Free',
            'subscription_from' => $current_date,
            'subscription_to' => $end_date,
        ]);

        if (isset($created_subscription)) {
            $multi_screen = Button::first()->multiplescreen;
            if (isset($multi_screen) && $multi_screen == 1) {
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
        }
        if (isset($menus) && count($menus) > 0) {

            return redirect()->route('home', $menus[0]->slug)->with('added', __('Your are now a subscriber !'));
        } else {
            return redirect('/')->with('added', __('Your are now a subscriber !'));
        }

    }
}
