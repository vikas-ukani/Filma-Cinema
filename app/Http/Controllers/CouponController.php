<?php

namespace App\Http\Controllers;

use App\Config;
use App\CouponCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \Stripe\Coupon;
use \Stripe\Stripe;


class CouponController extends Controller
{
   
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:coupon.view', ['only' => ['index']]);
        $this->middleware('permission:coupon.create', ['only' => ['create', 'store', 'ajaxstore']]);
        $this->middleware('permission:coupon.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:coupon.delete', ['only' => ['destroy', 'bulk_delete']]);
    }

    public function index()
    {
        $coupons = CouponCode::all();
        return view('admin.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $config = Config::first();
        return view('admin.coupon.create', compact('config'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $stripe_payment = Config::findOrFail(1)->stripe_payment;
        $request->validate([
            'coupon_code' => 'required',
            'duration' => 'required',
            'max_redemptions' => 'required|integer|min:0',
        ]);

        $input = $request->all();
        $redeem_by = Carbon::parse($input['redeem_by']);
        $redeem_by = strtotime($redeem_by->format('Y/m/d H:i'));

        if (!isset($input['percent_check'])) {
            $input['amount_off'] = $input['amount'];
            $input['percent_off'] = null;
        } elseif ($input['percent_check'] == 1) {
            $input['percent_off'] = $input['amount'];
            $input['amount_off'] = null;
        }

        if (isset($input['in_stripe'])) {
            $input['in_stripe'] = 1;
        } else {
            $input['in_stripe'] = 0;
        }

        try {

            if (isset($input['in_stripe']) && $input['in_stripe'] == 1) {

                $coupon = $coupon_generate = Coupon::create(array(
                    "percent_off" => $input['percent_off'],
                    "duration" => $input['duration'],
                    "duration_in_months" => $input['duration_in_months'],
                    "id" => $input['coupon_code'],
                    "currency" => $input['currency'],
                    "amount_off" => $input['amount_off'],
                    "max_redemptions" => $input['max_redemptions'],
                    "redeem_by" => $redeem_by,
                ));

            }
            CouponCode::create([
                "percent_off" => $input['percent_off'],
                "duration" => $input['duration'],
                "duration_in_months" => $input['duration_in_months'],
                "coupon_code" => $input['coupon_code'],
                "currency" => $input['currency'],
                "amount_off" => $input['amount_off'],
                "max_redemptions" => $input['max_redemptions'],
                "redeem_by" => $redeem_by,
                "in_stripe" => $input['in_stripe'],
            ]);

            return back()->with('added', __('Coupon has been added.'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = CouponCode::find($id);
        $config = Config::first();
        if (!isset($coupon)) {
            return back()->with('deleted', __('Coupon not found!'));
        } else {
            return view('admin.coupon.edit', compact('coupon', 'config'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $coupon = CouponCode::find($id);
        if (!isset($coupon) && $coupon == null) {
            return back()->with('deleted', 'Coupon not found!');
        } else {

            if ($coupon->in_stripe != 1) {
                try {
                    $request->validate([
                        'coupon_code' => 'required',
                        'duration' => 'required',
                        'max_redemptions' => 'required|integer|min:0',
                    ]);

                    $input = $request->all();
                    $redeem_by = Carbon::parse($input['redeem_by']);
                    $redeem_by = strtotime($redeem_by->format('Y/m/d H:i'));

                    if (!isset($input['percent_check'])) {
                        $input['amount_off'] = $input['amount'];
                        $input['percent_off'] = null;
                    } elseif ($input['percent_check'] == 1) {
                        $input['percent_off'] = $input['amount'];
                        $input['amount_off'] = null;
                    }

                    if (isset($input['in_stripe'])) {
                        $input['in_stripe'] = 1;
                    } else {
                        $input['in_stripe'] = 0;
                    }

                    $coupon->update([
                        "percent_off" => $input['percent_off'],
                        "duration" => $input['duration'],
                        "duration_in_months" => $input['duration_in_months'],
                        "coupon_code" => $input['coupon_code'],
                        "currency" => $input['currency'],
                        "amount_off" => $input['amount_off'],
                        "max_redemptions" => $input['max_redemptions'],
                        "redeem_by" => $redeem_by,
                        "in_stripe" => $input['in_stripe'],
                    ]);
                    return back()->with('updated', __('Coupon has been successfully updated!'));
                } catch (\Exception $e) {
                    return back()->with('deleted', $e->getMessage());
                }
            } else {
                return back()->with('deleted', __('Stripe coupon has been not editable !'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        Stripe::setApiKey(config('services.stripe.secret'));
        $coupon = CouponCode::findORFail($id);
        if ($coupon->in_stripe == 1) {
            try {
                $stripe_coupon = Coupon::retrieve($coupon->coupon_code);
                $stripe_coupon->delete();
            } catch (\Exception $e) {
                return back()->with('deleted', $e->getMessage());
            }
        }
        $coupon->delete();
        return back()->with('deleted', __('Coupon has been deleted'));
    }

    public function bulk_delete(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        Stripe::setApiKey(config('services.stripe.secret'));
        $validator = Validator::make($request->all(), [
            'checked' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('deleted', __('Please select one of them to delete'));
        }

        foreach ($request->checked as $checked) {
            $coupon = CouponCode::findORFail($checked);
            if ($coupon->in_stripe == 1) {
                try {
                    $stripe_coupon = Coupon::retrieve($coupon->coupon_code);
                    $stripe_coupon->delete();
                } catch (\Exception $e) {
                    return back()->with('deleted', $e->getMessage());
                }
            }
            $coupon->delete();
        }

        return back()->with('deleted', __('Coupons has been deleted'));
    }
}
