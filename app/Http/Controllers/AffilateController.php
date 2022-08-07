<?php

namespace App\Http\Controllers;

use App\Affilate;
use App\AffilateHistory;
use App\Config;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AffilateController extends Controller
{
    

    public function __construct()
    {
        $this->middleware('permission:affiliate.settings', ['only' => ['settings', 'update']]);
        $this->middleware('permission:affiliate.history', ['only' => ['reports']]);
    }

    public function settings()
    {

        $af_settings = Affilate::first();
        return view('admin.affilate.settings', compact('af_settings'));

    }

    public function update(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        Affilate::updateorCreate([
            'id' => 1,
        ], [
            'enable_affilate' => $request->enable_affilate ? 1 : 0,
            'code_limit' => strip_tags($request->code_limit),
            'refer_amount' => strip_tags($request->refer_amount),
            'about_system' => strip_tags($request->about_system),
            'enable_purchase' => $request->enable_purchase ? 1 : 0,
        ]);

        return back()->with('updated', __('Affiliate settings updated !'));

    }

    public function userdashboard()
    {

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

        return view('user.affiliate', compact('aff_history', 'earning', 'af_settings'));
    }

    public function reports()
    {

        $af_settings = Affilate::first();
        if (!$af_settings || $af_settings->enable_affilate != 1) {
            abort(404);
        }

        $data = AffilateHistory::with(['fromRefered' => function ($q) {
            return $q->select('id', 'name', 'email');
        }, 'user' => function ($q) {
            return $q->select('id', 'name', 'email');
        }])->whereHas('fromRefered')->whereHas('user');

        if (request()->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('refered_user', function ($row) {

                    return ucfirst($row->user->name) . ' (' . $row->user->email . ')';

                })
                ->addColumn('amount', function ($row) {
                    $currency_symbol = Config::first()->currency_symbol;
                    return '<i class="' . $currency_symbol . '">' . $row->amount;

                })
                ->addColumn('user', function ($row) {

                    return $row->fromRefered->name . ' (' . $row->fromRefered->email . ')';

                })

                ->addColumn('created_at', function ($row) {

                    return date('d/m/Y | h:i A', strtotime($row->created_at));

                })
                ->rawColumns(['refered_user', 'user', 'amount', 'created_at'])
                ->make(true);
        }

        return view('admin.affilate.dashboard');

    }
}
