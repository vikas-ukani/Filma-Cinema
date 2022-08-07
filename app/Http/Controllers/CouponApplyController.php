<?php

namespace App\Http\Controllers;

use App\CouponApply;
use App\CouponCode;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class CouponApplyController extends Controller
{
   
    public function get(Request $request)
    {

        $coupon = CouponCode::where('coupon_code', $request->coupon_code)->first();
        $plan = Package::where('id', $request->plan_id)->first();
        if (isset($coupon) && $coupon != null) {
            $current_date = Carbon::now();
            if ($current_date < $coupon->redeem_by) {
                if ($coupon->max_redemptions != 0) {
                    if ($coupon->duration == 'once') {
                        $user_id = Auth::user()->id;
                        $coupon_apply = CouponApply::where('user_id', $request->user_id)->where('coupon_id', $coupon->id)->first();
                        if (!$coupon_apply && $coupon_apply == null) {
                            $apply_coupon = CouponApply::create([
                                'user_id' => $user_id,
                                'coupon_id' => $coupon->id,
                                'redeem' => 1,
                            ]);
                        } else {
                            return back()->with('deleted', __('Coupon limit reached!'));
                        }
                    }

                    $query = $coupon->update(['max_redemptions' => $coupon->max_redemptions - 1]);

                    if ($coupon->amount_off != null) {
                        $amount = $coupon->amount_off;
                    } else {
                        $amount = ($plan->amount * $coupon->percent_off) / 100;
                    }

                    Session::put('coupon_applied', [
                        'code' => $coupon->coupon_code,
                        'amount' => $amount,
                        'id' => $coupon->id,
                    ]);

                    return back()->with('success', __('Coupon') . ucfirst($coupon->coupon_code) . __(' is applied successfully !'));
                } else {

                    return back()->with('deleted', __('Coupon is not available !'));
                }
            } else {

                return back()->with('deleted', __('Coupon Expired !'));
            }
        } else {

            return back()->with('deleted', __('Coupon Invalid !'));
        }
    }
}
