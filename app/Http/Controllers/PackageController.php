<?php

namespace App\Http\Controllers;

use App\Config;
use App\Menu;
use App\Multiplescreen;
use App\Package;
use App\PackageFeature;
use App\PaypalSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \Stripe\Plan;
use \Stripe\Stripe;

  
class PackageController extends Controller
{
   
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:package.view', ['only' => ['index']]);
        $this->middleware('permission:package.create', ['only' => ['create', 'store', 'ajaxstore']]);
        $this->middleware('permission:package.edit', ['only' => ['edit', 'update', 'pkgstatus']]);
        $this->middleware('permission:package.delete', ['only' => ['destroy', 'bulk_delete', 'softDelete']]);
    }

    public function pkgstatus(Request $request, $id)
    {
        $pkg = Package::findOrFail($id);
        $pkg->status = $request->status;
        $pkg->delete_status = 1;
        $pkg->save();

        if ($request->status == 1) {
            return back()->with('updated', __('Status has been to active!'));
        } else {
            return back()->with('updated',__('Status has been to deactive!'));
        }

    }

    public function index(Request $request)
    {
        $packages = Package::select('id', 'name','position','status','currency','amount','currency_symbol','interval','interval_count','trial_period_days')->OrderBy('position', 'ASC')->get();
        if ($request->ajax()) {
            return DataTables::of($packages)
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row->id;
                    },
                ])
                ->setRowClass('row1 sortable')
                ->make(true);
        }
        return view('admin.package.index', compact('packages'));
    }

    public function reposition(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if($request->ajax()){

            $posts = Package::all();
            foreach ($posts as $post) {
                foreach ($request->order as $order) {
                    if ($order['id'] == $post->id) {
                        \DB::table('packages')->where('id',$post->id)->update(['position' => $order['position']]);
                    }
                }
            }
            return response()->json('Update Successfully.', 200);

        }

       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = Menu::all();
        $p_feature = PackageFeature::all();
        return view('admin.package.create', compact('menus', 'p_feature'));
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

        if (config('services.stripe.secret') != null && config('services.stripe.key') != null) {
            Stripe::setApiKey(config('services.stripe.secret'));
        }

        $input = $request->all();

        $request->validate([
            'plan_id' => 'required',
            'interval' => 'required',
            'interval_count' => 'required',
            'currency_symbol' => 'required',
            // 'feature' => 'required|not_in:0'
        ], ['plan_id.required' => 'Plan ID Should Be Unique']);
        if (!isset($request->free)) {
            $request->validate([
                'amount' => 'required',
            ]);
            $input['amount'] = $request->amount;
        } else {
            $input['amount'] = '0.00';

        }
        $all_package = Package::where('delete_status', 0)->get();
        foreach ($all_package as $ap) {
            if ($ap->plan_id == $request->plan_id) {
                return back()->withInput();
                break;
            }
        }

        $input['plan_id'] = strtolower($input['plan_id']);

        if ($request->interval == 'year') {
            $request->validate([
                'interval_count' => 'required|max:1|numeric',
            ], ['interval_count.max' => 'Max value 1 Allowed']);
        }
        if ($request->interval == 'week') {
            $request->validate([
                'interval_count' => 'required|max:52|numeric',
            ], ['interval_count.max' => 'Max value 52 Allowed']);
        }
        if ($request->interval == 'month') {
            $request->validate([
                'interval_count' => 'required|max:12|numeric',
            ], ['interval_count.max' => 'Max value 12 Allowed']);
        }

        if (isset($request->download)) {
            $request->validate([
                'downloadlimit' => 'required|numeric',
            ], ['downloadlimit.required' => 'Please enter download limit']);

            $input['download'] = '1';

            $input['downloadlimit'] = $request->screens * $request->downloadlimit;

        } else {
            $input['download'] = '0';
            $input['downloadlimit'] = null;
        }
        $input['ads_in_app'] = isset($request->ads_in_app) ? '1' : '0';
        $input['ads_in_web'] = isset($request->ads_in_web) ? '1' : '0';
        // package_menu
        $menus = null;

        $package_create = Package::create($input);

        if (isset($request->menu) && count($request->menu) > 0) {
            $menus = $request->menu;
            for ($i = 0; $i < sizeof($menus); $i++) {
                if ($menus[$i] == 100) {
                    unset($menus);
                    $men = Menu::all();
                    foreach ($men as $key => $value) {
                        # code...
                        $menus[] = $value->id;
                    }
                    DB::table('package_menu')->insert(
                        array(
                            'menu_id' => $menus[$i],
                            'package_id' => $input['plan_id'],
                            'pkg_id' => $package_create->id,
                            'created_at' => date('Y-m-d h:i:s'),
                            'updated_at' => date('Y-m-d h:i:s'),
                        )
                    );

                } else {

                    DB::table('package_menu')->insert(
                        array(
                            'menu_id' => $menus[$i],
                            'package_id' => $input['plan_id'],
                            'pkg_id' => $package_create->id,
                            'created_at' => date('Y-m-d h:i:s'),
                            'updated_at' => date('Y-m-d h:i:s'),
                        )
                    );
                }

            }

        }
        try {
            $configs = Config::first();
            $payment = $configs->stripe_payment;
            if (!isset($request->free) && $payment == 1) {
                if (config('services.stripe.secret') != null && config('services.stripe.key') != null) {
                    Plan::create(array(
                        "id" => $input['plan_id'],
                        "currency" => $input['currency'],
                        "product" => [
                            "name" => $input['name'],
                        ],
                        "amount" => ($input['amount'] * 100),
                        "interval" => $input['interval'],
                        "interval_count" => $input['interval_count'],
                        "trial_period_days" => $input['trial_period_days'],
                    ));
                }
            }

        } catch (\Exception $e) {
            return $e->getMessage();
            return back()->with('deleted', $e->getMessage());
        }

        return redirect('admin/packages')->with('added', __('Package has been created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        $menus = Menu::all();
        $p_feature = PackageFeature::all();
        return view('admin.package.edit', compact('package', 'menus', 'p_feature'));
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
        $package = Package::findOrFail($id);
        if (config('services.stripe.secret') != null && config('services.stripe.key') != null) {
            // Set your secret key: remember to change this to your live secret key in production
            Stripe::setApiKey(config('services.stripe.secret'));
        }

        if (!isset($request->free)) {
            $request->validate([
                'amount' => 'required',
            ]);
        }

        $input = $request->all();

        if ($request->screens != null) {
            $input['screens'] = $request->screens;
        } else {
            $input['screens'] = 1;
        }

        if (!isset($request->free)) {
            $request->validate([
                'amount' => 'required',
            ]);
            $input['amount'] = $request->amount;
        } else {
            $input['amount'] = '0.00';

        }

        if (config('services.stripe.secret') != null && config('services.stripe.key') != null) {

            try {
                $stripe_plan = Plan::retrieve($package->plan_id);

                $stripe_plan->nickname = $input['name'];

                $stripe_plan->save();
            } catch (\Exception $e) {
            }
        }
        $deletemenu = DB::table('package_menu')->where('package_id', $input['plan_id'])->delete();

        if (isset($request->download)) {
            $request->validate([
                'downloadlimit' => 'required|numeric',
            ], ['downloadlimit.required' => 'Please enter download limit']);

            $input['download'] = '1';

            $input['downloadlimit'] = $package->screens * $request->downloadlimit;

        } else {
            $input['download'] = '0';
            $input['downloadlimit'] = null;
        }

        $input['ads_in_app'] = isset($request->ads_in_app) ? '1' : '0';
        $input['ads_in_web'] = isset($request->ads_in_web) ? '1' : '0';
        // package_menu
        $menus = null;

        $package->update($input);

        if (isset($request->menu) && count($request->menu) > 0) {
            $menus = $request->menu;
            for ($i = 0; $i < sizeof($menus); $i++) {
                if ($menus[$i] == 100) {
                    unset($menus);
                    $men = Menu::all();
                    foreach ($men as $key => $value) {
                        # code...
                        $menus[] = $value->id;
                    }
                    DB::table('package_menu')->insert(
                        array(
                            'menu_id' => $menus[$i],
                            'package_id' => $input['plan_id'],
                            'pkg_id' => $id,
                            'updated_at' => date('Y-m-d h:i:s'),
                        )
                    );

                } else {

                    DB::table('package_menu')->insert(
                        array(
                            'menu_id' => $menus[$i],
                            'package_id' => $input['plan_id'],
                            'pkg_id' => $id,
                            'updated_at' => date('Y-m-d h:i:s'),
                        )
                    );
                }

            }

        }

        return redirect('admin/packages')->with('updated',__('Package has been updated'));
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
        if (config('services.stripe.secret') != null && config('services.stripe.key') != null) {
            // Set your secret key: remember to change this to your live secret key in production
            Stripe::setApiKey(config('services.stripe.secret'));
        }

        $package = Package::findOrFail($id);
        try{
            if (config('services.stripe.secret') != null && config('services.stripe.key') != null) {
                try {
                    $stripe_plan = Plan::retrieve($package->plan_id);
                    $stripe_plan->delete();
                } catch (\Exception $e) {
                }
            }
            if(isset($package)){
                PaypalSubscription::where('package_id',$id)->delete();
                Multiplescreen::where('pkg_id',$id)->delete();
            }
            $package->status = 0;
            $package->save();
            $package->delete();
            return redirect('admin/packages')->with('deleted', __('Package has been deleted'));
        }catch(\Exception $e){
            return redirect('admin/packages')->with('deleted',$e->getMessage());
        }
       
       
    }

    public function softDelete(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $package = Package::findOrFail($id);
        $package->delete_status = 0;
        $package->status = 0;

        $package->save();
        return redirect('admin/packages')->with('deleted', __('Package has been deleted'));
    }

    public function bulk_delete(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if (config('services.stripe.secret') != null && config('services.stripe.key') != null) {
            // Set your secret key: remember to change this to your live secret key in production
            Stripe::setApiKey(config('services.stripe.secret'));
        }

        $validator = Validator::make($request->all(), [
            'checked' => 'required',
        ]);

        if ($validator->fails()) {

            return back()->with('deleted', __('Please select one of them to delete'));
        }

        foreach ($request->checked as $checked) {

            $package = Package::findOrFail($checked);
            if (config('services.stripe.secret') != null && config('services.stripe.key') != null) {
                try
                {
                    $stripe_plan = Plan::retrieve($package->plan_id);
                    $stripe_plan->delete();
                } catch (\Exception $e) {

                }
            }
            $package->delete_status = 0;
            $package->status = 0;
            $package->save();

        }

        return back()->with('deleted',__('Plans has been deleted'));
    }
}
