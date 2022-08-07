<?php

namespace App\Http\Controllers;

use App\UserWalletHistory;
use App\WalletSettings;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Facades\DataTables;

class WalletSettingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WalletSettingController
    |--------------------------------------------------------------------------
    |
    | This controller holds the logics and functionality of wallet settings.
    |
     */

    /**
     * @return view of wallet settings
     */

    public function index(Request $request)
    {
        $wallet = WalletSettings::first();
        $wallet_transactions = UserWalletHistory::orderBy('id', 'DESC')->get();
        if ($request->ajax()) {
            return DataTables::of($wallet_transactions)

                ->addIndexColumn()
                ->editColumn('user', function ($row) {
                    return $row->wallet->user->name;
                })

                ->editColumn('type', function ($row) {
                    if ($row->type == 'Credit') {
                        return '<span class="text-green">' . $row->type . '</span>';
                    } else {
                        return '<span class="text-red">' . $row->type . '</span>';
                    }
                })
                ->editColumn('amount', function ($row) {
                    if ($row->type == 'Credit') {
                        return "<span class='text-green'><b> + $row->amount </b></span>";
                    } else {
                        return "<span class='text-red'><b> -  $row->amount </b></span>";
                    }
                })

                ->addColumn('log', function ($row) {
                    return $row->log;
                })
                ->rawColumns(['user', 'type', 'amount', 'log'])
                ->make(true);
        }

        return view('admin.wallet.setting', compact('wallet', __('wallet_transactions')));
    }

    /**
     * This function holds the funncality to update wallet settings.
     */

    public function update(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        try {

            /** Get the wallet settings */

            $settings = WalletSettings::first();
            $input = $request->all();

            if ($settings) {

                if (!isset($input['enable_wallet'])) {
                    $input['enable_wallet'] = 0;
                } else {
                    $input['enable_wallet'] = 1;
                }

                if (!isset($input['paytm_enable'])) {
                    $input['paytm_enable'] = 0;
                } else {
                    $input['paytm_enable'] = 1;
                }

                if (!isset($input['paypal_enable'])) {
                    $input['paypal_enable'] = 0;
                } else {
                    $input['paypal_enable'] = 1;
                }

                if (!isset($input['stripe_enable'])) {
                    $input['stripe_enable'] = 0;
                } else {
                    $input['stripe_enable'] = 1;
                }

                $settings->update($input);

            } else {

                /** Create new wallet settings if not exist */

                $settings = new WalletSettings;

                if (!isset($input['enable_wallet'])) {
                    $input['enable_wallet'] = 0;
                } else {
                    $input['enable_wallet'] = 1;
                }

                if (!isset($input['paytm_enable'])) {
                    $input['paytm_enable'] = 0;
                } else {
                    $input['paytm_enable'] = 1;
                }

                if (!isset($input['paypal_enable'])) {
                    $input['paypal_enable'] = 0;
                } else {
                    $input['paypal_enable'] = 1;
                }

                if (!isset($input['stripe_enable'])) {
                    $input['stripe_enable'] = 0;
                } else {
                    $input['stripe_enable'] = 1;
                }

                $settings->create($input);
            }

            return back()->with('added', __('Wallet Settings Updated !'));

        } catch (\Exception $e) {

            /** Catch the error and @return back to previous location with error message */

            return back()->with('deleted', $e->getMessage());
        }
    }

}
