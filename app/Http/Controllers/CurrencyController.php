<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Nwidart\Modules\Facades\Module;
use Torann\Currency\Facades\Currency;
use Yajra\DataTables\Facades\DataTables;


class CurrencyController extends Controller
{
   
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function saveSetting(Request $request)
    {
        $env_keys_save = DotenvEditor::setKeys([
            'OPEN_EXCHANGE_RATE_KEY' => $request->OPEN_EXCHANGE_RATE_KEY,
        ]);

        $env_keys_save->save();

        return back()->with('added', __('Exchange key has been updated !'));
    }

    public function index()
    {

        if (request()->ajax()) {
            $currency = currency()->getCurrencies();
            return DataTables::of($currency)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row['name'];
                })
                ->addColumn('code', function ($row) {
                    return $row['code'];
                })
                ->addColumn('symbol', function ($row) {
                    return $row['symbol'];
                })
                ->addColumn('exchange_rate', function ($row) {
                    return $row['exchange_rate'];
                })
                ->editColumn('created_at', function ($row) {
                    return $row['created_at'];
                })
                ->editColumn('action', 'admin.currency.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.currency.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin.currency.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $request->validate([
            'code' => 'required|string|max:3',
        ]);

        Artisan::call('currency:manage add ' . $request->code);

        Artisan::call('currency:update -o');

        return back()->with('success', __('Currency added !'));

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin.currency.edit');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($code)
    {
        currency()->delete($code);
        return back()->with('deleted', __('Currency deleted !'));

    }

    public function auto_update_currency(Request $request)
    {

        if ($request->ajax()) {

            try {
                Artisan::call('currency:update -o');

                return response()->json(['msg' => __('Currency Rate Auto Update Successfully !')]);

            } catch (\Exception $e) {
                return response()->json(['msg' => $e->getMessage()]);
            }

        }

    }

    public function checkoutCurrency($id)
    {

        $currency = DB::table('currencies')->where('id', $id)->first();

        $payments = array("stripe", "paypal", "braintree", "coinpay", "omise", "payhere", "flutterrave", "paystack", "instamojo", "paytm", "mollie", "cashfree", "payu", "razorpay");

        //AuthorizeNet
        if (Module::has('AuthorizeNet') && Module::find('AuthorizeNet')->isEnabled()) {
            array_push($payments, 'authorizenet');
        }

        //Bkash
        if (Module::has('Bkash') && Module::find('Bkash')->isEnabled()) {
            array_push($payments, 'bkash');
        }

        //DPO
        if (Module::has('DPOPayment') && Module::find('DPOPayment')->isEnabled()) {
            array_push($payments, 'dpopayment');
        }

        //Esewa
        if (Module::has('Esewa') && Module::find('Esewa')->isEnabled()) {
            array_push($payments, 'esewa');
        }

        //Midtrains
        if (Module::has('Midtrains') && Module::find('Midtrains')->isEnabled()) {
            array_push($payments, 'midtrains');
        }

        //MPesa
        if (Module::has('MPesa') && Module::find('MPesa')->isEnabled()) {
            array_push($payments, 'mpesa');
        }

        //Paytab
        if (Module::has('Paytab') && Module::find('Paytab')->isEnabled()) {
            array_push($payments, 'paytab');
        }

        //Senangpay
        if (Module::has('Senangpay') && Module::find('Senangpay')->isEnabled()) {
            array_push($payments, 'senangpay');
        }
        //sManager
        if (Module::has('Smanager') && Module::find('Smanager')->isEnabled()) {
            array_push($payments, 'smanager');
        }

        //SquarePay
        if (Module::has('SquarePay') && Module::find('SquarePay')->isEnabled()) {
            array_push($payments, 'squarepay');
        }

        //Worldpay
        if (Module::has('Worldpay') && Module::find('Worldpay')->isEnabled()) {
            array_push($payments, 'worldpay');
        }

        $currency_payments = $currency->payment_method;

        $currency_payments = (array) json_decode($currency_payments, true);

        return view('admin.currency.checkout', compact('id', 'payments', 'currency', 'currency_payments'));
    }

    public function checkoutPayment(Request $request)
    {

        try {
            DB::table('currencies')->where('id', $request->currecny_id)->update([
                'payment_method' => $request->payment_method,
            ]);
            return back()->with('updated', __('Payment Method has been updated!'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage);
        }

    }
}
