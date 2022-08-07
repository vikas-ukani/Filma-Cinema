<?php

namespace App\Http\Controllers;

use App\Button;
use App\Menu;
use App\Multiplescreen;
use App\Package;
use App\PaypalSubscription;
use App\UserWallet;
use App\UserWalletHistory;
use Auth;
use Cookie;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class SubscriptionController extends Controller
{
    public function subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount)
    {

        $user = auth()->user();
        $current_date = Carbon::now();
        $plan = Package::find($plan_id);
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
        if($plan->trial_period_days != NULL){
            $end_date = $end_date->addDays($plan->trial_period_days);
        }

        DB::beginTransaction();

        $created_subscription = PaypalSubscription::create([
            'user_id' => $user->id,
            'payment_id' => $payment_id,
            'user_name' => $user->name,
            'package_id' => $plan_id,
            'price' => $payment_amount,
            'status' => $payment_status,
            'method' => $payment_method,
            'subscription_from' => $current_date,
            'subscription_to' => $end_date,
        ]);

        if (isset($created_subscription)) {
            Session::forget('coupon_applied');
            $multi_screen = Button::first()->multiplescreen;
            if (isset($multi_screen) && $multi_screen == 1) {
                $screen = $plan->screens;
                if ($screen > 0) {
                    $multiplescreen = Multiplescreen::where('user_id', $user->id)->first();
                    if (isset($multiplescreen)) {
                        $multiplescreen->update([
                            'pkg_id' => $plan_id,
                            'user_id' => $user->id,
                            'screen1' => $screen >= 1 ? $user->name : null,
                            'screen2' => $screen >= 2 ? 'Screen2' : null,
                            'screen3' => $screen >= 3 ? 'Screen3' : null,
                            'screen4' => $screen >= 4 ? 'Screen4' : null,
                        ]);
                    } else {
                        $multiplescreen = Multiplescreen::create([
                            'pkg_id' => $plan_id,
                            'user_id' => $user->id,
                            'screen1' => $screen >= 1 ? $user->name : null,
                            'screen2' => $screen >= 2 ? 'Screen2' : null,
                            'screen3' => $screen >= 3 ? 'Screen3' : null,
                            'screen4' => $screen >= 4 ? 'Screen4' : null,
                        ]);
                    }
                }
            }

            if ($payment_method == 'wallet') {
                $conv_wallet_amount = $payment_amount;
                $wallet = UserWallet::where('user_id', Auth::user()->id)->first();

                if (isset($wallet)) {

                    $wallet->balance = $wallet->balance - $conv_wallet_amount;
                    $wallet->save();

                    //adding log in history

                    $walletlog = new UserWalletHistory();
                    $walletlog->wallet_id = $wallet->id;
                    $walletlog->type = 'Debit';
                    $walletlog->log = 'Payment for subscription ' . $payment_id;
                    $walletlog->amount = $conv_wallet_amount;
                    $walletlog->txn_id = $payment_id;
                    $walletlog->save();

                }
            }

        }

        \Cookie::forget('plan');
        DB::commit();
        $planmenus = DB::table('package_menu')->where('package_id', $plan->plan_id)->get();
        if (isset($planmenus)) {
            foreach ($planmenus as $key => $value) {
                $menus[] = $value->menu_id;
            }
            if (isset($menus)) {
                $nav_menus = Menu::whereIn('id', $menus)->get();
            }
        }

        if (isset($nav_menus) && count($nav_menus) > 0) {
            return redirect()->route('home', $nav_menus[0]->slug)->with('added', __('Your are now a subscriber !'));
        } else {
            return redirect('/')->with('added',__('Your are now a subscriber !'));
        }
    }

}
