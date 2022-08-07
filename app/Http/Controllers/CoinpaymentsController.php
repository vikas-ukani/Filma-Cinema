<?php
namespace App\Http\Controllers;

use App\Multiplescreen;
use App\Package;
use App\PaypalSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CoinpaymentsController extends Controller
{
    

    public function purchaseItems(Request $request)
    {
        $plan = Package::findOrFail($request->plan_id);
        $customer = Auth::user();
        $cpp = DB::select('SELECT * FROM `cps_cpp` WHERE `userid` = ? AND `plan` = ? AND `expire` > ? ORDER BY `expire` DESC LIMIT 1', [$customer->id, $plan->id, time() - 900]);
        if (empty($cpp)) {
            $transaction = (array) \Coinpayments::createTransactionSimple($plan->amount, "EUR", $request->currency, [
                "item_number" => $plan->id,
                "item_name" => $plan->name,
                "buyer_email" => $customer->email,
                "buyer_name" => $customer->name,
                "custom" => json_encode([
                    "userid" => $customer->id,
                    "username" => $customer->name,
                    "planid" => $plan->id,
                    "price" => $plan->amount,
                ]),
                "ipn_url" => "http://66.70.129.55/payment/coinpayment/callback",
            ]);
            if (!empty($transaction[chr(0) . '*' . chr(0) . "original"]["txn_id"])) {
                $tinfo = $transaction[chr(0) . '*' . chr(0) . "original"];
                $expire = $tinfo["timeout"] + time() - 1;
                DB::insert('INSERT INTO `cps_cpp` VALUES(?,?,?,?)', [$customer->id, $expire, json_encode($tinfo), $plan->id]);
                $etinfo = (array) \Coinpayments::getTransactionInfo($tinfo["txn_id"]);
                return view('user.purchasecps', compact('etinfo', 'tinfo', 'plan', 'customer'));
            } else {
                echo "Sorry, an error has occured. Please try again or contact an admin.";
            }
        } else {
            $results = (array) DB::select('SELECT * FROM `cps_cpp` WHERE `userid` = ? AND `plan` = ? AND `expire` > ? ORDER BY `expire` DESC LIMIT 1', [$customer->id, $plan->id, time() - 900]);
            $result = (array) $results[0];
            $tinfo = json_decode($result["info"], true);
            $etinfo = (array) \Coinpayments::getTransactionInfo($tinfo["txn_id"]);
            return view('user.purchasecps', compact('etinfo', 'tinfo', 'plan', 'customer'));
        }
    }

    public function cpscheck(Request $request)
    {
        $customer = Auth::user();
        if (!empty($customer)) {
            $results = (array) DB::select('SELECT * FROM `cps_cpp` WHERE `userid` = ?', [$customer->id]);
            foreach ($results as $result) {
                $result = (array) $result;
                $tinfo = json_decode($result["info"], true);
                if (!empty($result["plan"])) {
                    $plan = Package::findOrFail($result["plan"]);
                    $etinfo = (array) \Coinpayments::getTransactionInfo($tinfo["txn_id"]);
                    if ($etinfo["status"] >= 100) {
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
                            'payment_id' => rand(10, 9999999999999),
                            'user_name' => $customer->name,
                            'package_id' => $plan->id,
                            'price' => $plan->amount,
                            'status' => '1',
                            'method' => 'coinpayments',
                            'subscription_from' => $current_date,
                            'subscription_to' => $end_date,
                        ]);
                        DB::table('cps_cpp')->where('info', $result["info"])->delete();
                    }
                    if ($etinfo["status"] < 0 || $etinfo["time_expires"] + 10 < time()) {
                        DB::table('cps_cpp')->where('info', $result["info"])->delete();
                    }
                }

            }
        }
        echo 'End of CPS Check';
    }

    public function validateIpn(Request $request)
    {
        try {
            $ipn = \Coinpayments::validateIPNRequest($request);
            if ($ipn->isApi()) {
                if ($request->status >= 100) {
                    $current_date = Carbon::now();
                    $end_date = null;
                    $plan = Package::findOrFail($request->item_number);
                    $custom = json_decode($request->custom, true);
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
                        'user_id' => $custom["userid"],
                        'payment_id' => rand(10, 9999999999999),
                        'user_name' => $custom["username"],
                        'package_id' => $request->item_number,
                        'price' => $plan->amount,
                        'status' => '1',
                        'method' => 'coinpayments',
                        'subscription_from' => $current_date,
                        'subscription_to' => $end_date,
                    ]);

                    if ($created_subscription) {
                        // Si rÃ©ussi
                        if (isset($mlt_screen) && $mlt_screen == 1) {
                            $auth = Auth::user();
                            $screen = $plan->screens;
                            if ($screen > 0) {
                                $multiplescreen = Multiplescreen::where('user_id', $auth->id)->first();
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

                    }
                }
            }
            return redirect('/')->with('added', __('Your are now a subscriber !'));
        } catch (\Exception $e) {
            $ipn = $e->getIpn();
            return back()->with('deleted', $e->getMessage());
        }

    }
}
