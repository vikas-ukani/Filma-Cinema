<?php
namespace App\Http\Controllers;
use Redirect;

use App\Config;
use App\Mail\WelcomeUser;
use App\Multiplescreen;
use App\Package;
use App\PaypalSubscription;
use App\User;
use App\{Country, State, City};
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Mail;
use Response;
use Spatie\Permission\Models\Role;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:users.view', ['only' => ['index', 'change_subscription_show']]);
        $this->middleware('permission:users.create', ['only' => ['create', 'store', 'ajaxstore']]);
        $this->middleware('permission:users.edit', ['only' => ['edit', 'update', 'change_subscription']]);
        $this->middleware('permission:users.delete', ['only' => ['destroy', 'bulk_delete']]);
    }

    public function getPosts()
    {

        $users = DB::table('users')->select('*');

        return DataTables::of($users)->make(true);
    }

    public function index(Request $request)
    {
        $auth_id = Auth::id();

        $users = DB::table('users')->select('id', 'name', 'is_admin','email', 'created_at', 'updated_at', 'is_blocked')
            ->where('id', '!=', $auth_id)->get();

        if ($request->ajax()) {
            return \Datatables::of($users)->addIndexColumn()->addColumn('checkbox', function ($user) {
                $html = '<div class="inline">
                    <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="' . $user->id . '" id="checkbox' . $user->id . '">
                    <label for="checkbox' . $user->id . '" class="material-checkbox"></label>
                  </div>';

                return $html;
            })->addColumn('created_at', function ($user) {
                return date('F d, Y', strtotime($user->created_at));

            })->addColumn('updated_at', function ($user) {

                return date('F d, Y', strtotime($user->updated_at));
            })->editColumn('status', function ($user) {
                if ($user->is_blocked == 0) {
                    return '<button class="btn btn-danger btn-sm status" data-id="' . $user->id . '">' . __('adminstaticwords.Block') . '</button>';
                } else {
                    return '<button class="btn btn-success btn-sm status" data-id="' . $user->id . '">' . __('adminstaticwords.Unblock') . '</button>';
                }

            })->addColumn('action', 'admin.users.action')->rawColumns(['checkbox', 'created_at', 'updated_at', 'action', 'status'])
                ->make(true);
        }

        return view('admin.users.index', compact('users'));
    }

    // change is_blocked in datatable
    public function changestatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->is_blocked == 0) {
            $user->is_blocked = 1;
        } else {
            $user->is_blocked = 0;
        }
        $user->update();
        return Response::json($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = DB::table('roles')->select('id', 'name')->get();
        $country=Country::all();
        $state=State::all();
        $city=City::all();
        return view('admin.users.create', compact('roles','country','state','city'));
    }

    public function getcountry()
    {
        $data['countries'] = Country::get(["name","id"]);
        return view('country-state-city',$data);
    }
    public function getState(Request $request)
    {
        $data['states'] = State::where("country_id",$request->country_id)
                    ->get(["name","id"]);
        return response()->json($data);
    }
    public function getCity(Request $request)
    {
        $data['cities'] = City::where("state_id",$request->state_id)
                    ->get(["name","id"]);
        return response()->json($data);
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
        $request->validate(['name' => 'required', 'image' => 'nullable|image|mimes:jpeg,png,jpg', 'email' => 'required|email|unique:users','mobile' => 'unique:users', 'password' => 'required', 'confirm_password' => 'required|same:password']);

        $input = $request->all();

        $age = $request->age;

       /* if (isset($request->dob)) {
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $input['age'] = $diff->format('%y');

        }*/
        if ($file = $request->file('image')) {
            $name = 'user_' . $file->getClientOriginalName();
            $file->move('images/users', $name);
            $input['image'] = $name;
        }

        if ($request->role == 'admin' || $request->role == 'Admin') {
            $input['is_admin'] = 1;
            $input['is_assistant'] = 0;
        } elseif ($request->role == 'producer' || $request->role == 'Producer') {
            $input['is_assistant'] = 1;
            $input['is_admin'] = 0;
        } elseif ($request->role == 'user' || $request->role == 'User') {
            $input['is_admin'] = 0;
            $input['is_assistant'] = 0;

        } else {
            $input['is_assistant'] = 0;
            $input['is_admin'] = 1;
        }

        $input['status'] = 1;

        // $this->save($user,$request,$name,$age,$status,$role_id);

        $input['password'] = bcrypt($request['password']);

        $user = User::create($input);

        $user->assignRole($request->role);

        $config = Config::first();

        if ($config->wel_eml == 1) {
            try {
                if (env('MAIL_HOST') != null && env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null) {
                    Mail::to($input['email'])->send(new WelcomeUser($user));
                }

            } catch (\Exception $e) {
                return back()->with('deleted', $e->getMessage());
            }

        }
        return back()->with('added', __('User has been created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $country=Country::all();
        $state=State::all();
        $city=City::all();
        $roles = \DB::table('roles')->select('id', 'name')->get();
        return view('admin.users.edit', compact('user', 'roles', 'country','state','city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_age(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $input = $request->all();

        //$age = $request->age;

       /* if (isset($request->dob)) {
            $user->dob = $request->dob;
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $age = $diff->format('%y');
            $user->age = $age;

        }*/
        $user->age = $request->age;

        $user->mobile = $request->mobile;

        $user->save();
        return back()->with('added', __('Your Age Has Been Updated'));
    }

    public function update_address(Request $request){
        $id = Auth::user()->id;
        $user = User::find($id);
        $input = $request->all();
        $country = Country::all();
        $state= State::all();
        $city = City::all();

        $user->address = $request->address;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->save();
        return back()->with('added', __('Your Address Has Been Updated'));

    }

    public function update_image(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);

        if ($file = $request->file('image')) {
            $validator = Validator::make(
                [
                    'image' => $request->image,
                    'extension' => strtolower($request->image->getClientOriginalExtension()),
                ],
                [
                    'image' => 'required',
                    'extension' => 'required|in:jpg,jpeg,png,webp,gif',
                ]
            );
           
            if(!$validator->fails())
            {
                $image = 'user_' . time() . $file->getClientOriginalName();
                if ($user->image != null) {
                    $content = @file_get_contents(public_path() . '/images/users/' . $user->image);
                    if ($content) {
                        unlink(public_path() . "/images/users/" . $user->image);
                    }
                }

                
                $img = Image::make($file->path());

                $img->resize(300, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/users') . '/' . $image);

                $user->image = $image;
                $user->save();
                return response()->json([
                    'message'   => 'Your image Has Been Updated',
                    'class_name'  => 'alert-success'
                ]);
            }
            else
            {
                return response()->json([
                    'message'   => 'Invalid file format Please use jpg,jpeg and png image format !',
                    'class_name'  => 'alert-danger'
                ]);
            }
          

        }

    }

    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted',__('This action is disabled in the demo !'));
        }
        $user = User::find($id);
        $country=Country::all();
        $state=State::all();
        $city=City::all();

        $request->validate(['name' => 'required', 'image' => 'nullable|image|mimes:jpeg,png,jpg', 'email' => 'required|email', 'mobile' => 'numeric', 'role' => 'nullable', 'address' => 'nullable', 'country' => 'nullable', 'state' => 'nullable', 'city' => 'nullable','confirm_password' => 'same:password']);

        /*$dateOfBirth = $request->dob;
        if (isset($dateOfBirth)) {
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $input['age'] = $diff->format('%y');
        }*/

        if ($file = $request->file('image')) {
            $name = 'user_' . $file->getClientOriginalName();
            if ($user->image != '') {
                unlink(public_path() . '/images/users/' . $user->image);
            }
            $file->move('images/users', $name);
            $input['image'] = $name;
        }

        if ($request->role == 'admin' || $request->role == 'Admin') {
            $input['is_admin'] = 1;
            $input['is_assistant'] = 0;
        } elseif ($request->role == 'producer' || $request->role == 'Producer') {
            $input['is_assistant'] = 1;
            $input['is_admin'] = 0;
        } elseif ($request->role == 'user' || $request->role == 'User') {
            $input['is_admin'] = 0;
            $input['is_assistant'] = 0;

        } else {
            $input['is_assistant'] = 0;
            $input['is_admin'] = 1;
        }

        $input['status'] = 1;

        $input['password'] = isset($request->password) ? bcrypt($request['password']) : $user->password;
        $user->update($input);
        $user->address = $request->address;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->age = $request->age;
        $user->mobile = $request->mobile;
        $user->assignRole($request->role);
        $user->save();

        return redirect('admin/users')->with('updated', __('User has been updated'));
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
        $user = User::findOrFail($id);

        if ($user->image) {
            unlink(public_path() . 'images/users/' . $user->image);
        }
        if (isset($user->paypal_subscriptions)) {
            foreach ($user->paypal_subscriptions as $subscribe_user) {
                $subscribe_user->delete();
            }
        }

        $user->delete();
        return back()
            ->with('deleted', __('User has been deleted'));
    }

    public function bulk_delete(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $validator = Validator::make($request->all(), ['checked' => 'required']);

        if ($validator->fails()) {

            return back()
                ->with('deleted', __('Please select one of them to delete'));
        }

        foreach ($request->checked as $checked) {

            $user = User::findOrFail($checked);

            if (isset($user->paypal_subscriptions)) {
                foreach ($user->paypal_subscriptions as $subscribe_user) {
                    $subscribe_user->delete();
                }
            }

            User::destroy($checked);
        }

        return back()->with('deleted', __('Users has been deleted'));
    }

    public function change_subscription_show($id)
    {

        $user = User::findOrFail($id);

        $plans = Package::all();

        $user_stripe_plan = null;
        $last_payment = null;
        if (isset($plans)) {
            if ($user->stripe_id != null) {
                foreach ($plans as $plan) {
                    if ($user->subscribed($plan->plan_id)) {
                        $user_stripe_plan = $plan;
                    }
                }
            }
            if (isset($user->paypal_subscriptions) && count($user->paypal_subscriptions) > 0) {
                //Check Paypal Subscription of user
                $last_payment = $user
                    ->paypal_subscriptions
                    ->last();
            }

        }

        return view('admin.users.change_sub', compact('user', 'user_stripe_plan', 'last_payment', 'plans'));
    }

    public function change_subscription(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $request->validate(['plan_id' => 'required', 'user_id' => 'required']);

        $user = User::findOrFail($request->user_id);
        $change_plan = Package::findOrFail($request->plan_id);
        $multiplescreen = Multiplescreen::where('user_id', $request->user_id)->first();

        if ($request->user_stripe_plan_id != null) {

            $user_stripe_plan = Package::findOrFail($request->user_stripe_plan_id);
            $user->subscription($user_stripe_plan->plan_id)->swap($change_plan->plan_id);
            return back()->with('added', 'User subscription has been changed!');

        } else if ($request->last_payment_id != null) {

            $last_payment = PaypalSubscription::findOrFail($request->last_payment_id);
            $current_date = Carbon::now();
            $end_date = null;

            if ($change_plan->interval == 'month') {
                $end_date = Carbon::now()->addMonths($change_plan->interval_count);
            } else if ($change_plan->interval == 'year') {
                $end_date = Carbon::now()->addYears($change_plan->interval_count);
            } else if ($change_plan->interval == 'week') {
                $end_date = Carbon::now()->addWeeks($change_plan->interval_count);
            } else if ($change_plan->interval == 'day') {
                $end_date = Carbon::now()->addDays($change_plan->interval_count);
            }
            if($change_plan->trial_period_days != NULL){
                $end_date = $end_date->addDays($change_plan->trial_period_days);
            }

            $last_payment->package_id = $change_plan->id;
            $last_payment->price = $change_plan->amount;
            $last_payment->status = 1;
            $last_payment->method = 'by Admin';
            $last_payment->subscription_from = $current_date;
            $last_payment->subscription_to = $end_date;
            $last_payment->save();

            if (isset($mlt_screen) && $mlt_screen == 1) {
                if (isset($multiplescreen)) {

                    $multiplescreen->delete();
                    $muser = new Multiplescreen;

                    $muser->user_id = $user->id;

                    if ($change_plan->screens == 1) {
                        $muser->screen1 = $user->name;

                    } elseif ($change_plan->screens == 2) {
                        $muser->screen1 = $user->name;
                        $muser->screen2 = "Screen1";
                    } elseif ($change_plan->screens == 3) {
                        $muser->screen1 = $user->name;
                        $muser->screen2 = "Screen2";
                        $muser->screen3 = "Screen3";
                    } elseif ($change_plan->screens == 4) {
                        $muser->screen1 = $user->name;
                        $muser->screen2 = "Screen2";
                        $muser->screen3 = "Screen3";
                        $muser->screen4 = "Screen4";
                    }

                    $muser->pkg_id = $change_plan->id;
                    $muser->save();

                } else {

                    $muser = new Multiplescreen;

                    $muser->user_id = $user->id;
                    $muser->pkg_id = $change_plan->id;

                    if ($change_plan->screens == 1) {
                        $muser->screen1 = $user->name;

                    } elseif ($change_plan->screens == 2) {
                        $muser->screen1 = $user->name;
                        $muser->screen2 = "Screen1";
                    } elseif ($change_plan->screens == 3) {
                        $muser->screen1 = $user->name;
                        $muser->screen2 = "Screen2";
                        $muser->screen3 = "Screen3";
                    } elseif ($change_plan->screens == 4) {
                        $muser->screen1 = $user->name;
                        $muser->screen2 = "Screen2";
                        $muser->screen3 = "Screen3";
                        $muser->screen4 = "Screen4";
                    }

                    $muser->save();
                }
            }

            return back()->with('added', __('User subscription has been changed!'));

        } else if ($request->user_stripe_plan_id != null && $request->last_payment_id != null) {

            $current_date = Carbon::now();
            $end_date = null;

            if ($change_plan->interval == 'month') {
                $end_date = Carbon::now()->addMonths($change_plan->interval_count);
            } else if ($change_plan->interval == 'year') {
                $end_date = Carbon::now()->addYears($change_plan->interval_count);
            } else if ($change_plan->interval == 'week') {
                $end_date = Carbon::now()->addWeeks($change_plan->interval_count);
            } else if ($change_plan->interval == 'day') {
                $end_date = Carbon::now()->addDays($change_plan->interval_count);
            }

            if (isset($mlt_screen) && $mlt_screen == 1) {
                $muser = new Multiplescreen;
                $exist = Multiplescreen::where('user_id', $user->id)->first();

                $getpkgid;
                $screen;
                if (!isset($exist)) {

                    foreach ($user->paypal_subscriptions as $value) {

                        if ($value->status == 1) {

                            $getpkgid = $value->package_id;

                            $pkg = Package::where('id', $value->package_id)->first();

                            if (isset($pkg)) {
                                $screen = $pkg->screens;
                                $muser->pkg_id = $pkg->id;

                                $muser->user_id = $user->id;

                                if ($screen == 1) {
                                    $muser->screen1 = $user->name;

                                } elseif ($screen == 2) {
                                    $muser->screen1 = $user->name;
                                    $muser->screen2 = "Screen2";
                                } elseif ($screen == 3) {
                                    $muser->screen1 = $user->name;
                                    $muser->screen2 = "Screen2";
                                    $muser->screen3 = "Screen3";
                                } elseif ($screen == 4) {
                                    $muser->screen1 = $user->name;
                                    $muser->screen2 = "Screen2";
                                    $muser->screen3 = "Screen3";
                                    $muser->screen4 = "Screen4";
                                }

                                $muser->save();

                            }
                        }
                    }

                } else {

                    $exist->delete();
                    $screen = $pkg->screens;
                    $muser->pkg_id = $pkg->id;

                    $muser->user_id = $user->id;

                    if ($screen == 1) {
                        $muser->screen1 = $user->name;

                    } elseif ($screen == 2) {
                        $muser->screen1 = $user->name;
                        $muser->screen2 = "Screen2";
                    } elseif ($screen == 3) {
                        $muser->screen1 = $user->name;
                        $muser->screen2 = "Screen2";
                        $muser->screen3 = "Screen3";
                    } elseif ($screen == 4) {
                        $muser->screen1 = $user->name;
                        $muser->screen2 = "Screen2";
                        $muser->screen3 = "Screen3";
                        $muser->screen4 = "Screen4";
                    }

                    $muser->save();
                }
            }

            $created_subscription = PaypalSubscription::create(['user_id' => $user->id, 'payment_id' => 'by admin', 'user_name' => $user->name, 'package_id' => $change_plan->id, 'price' => $change_plan->amount, 'status' => 1, 'method' => 'by Admin', 'subscription_from' => $current_date, 'subscription_to' => $end_date]);

            return back()->with('added', __('User subscription has been changed!'));
        } else {

            if (isset($mlt_screen) && $mlt_screen == 1) {
                $muser = new Multiplescreen;

                $muser->user_id = $user->id;
                $muser->pkg_id = $change_plan->id;

                if ($change_plan->screens == 1) {
                    $muser->screen1 = $user->name;

                } elseif ($change_plan->screens == 2) {
                    $muser->screen1 = $user->name;
                    $muser->screen2 = "Screen1";
                } elseif ($change_plan->screens == 3) {
                    $muser->screen1 = $user->name;
                    $muser->screen2 = "Screen2";
                    $muser->screen3 = "Screen3";
                } elseif ($change_plan->screens == 4) {
                    $muser->screen1 = $user->name;
                    $muser->screen2 = "Screen2";
                    $muser->screen3 = "Screen3";
                    $muser->screen4 = "Screen4";
                }

                $muser->save();
            }

            $current_date = Carbon::now();
            $end_date = null;

            if ($change_plan->interval == 'month') {
                $end_date = Carbon::now()->addMonths($change_plan->interval_count);
            } else if ($change_plan->interval == 'year') {
                $end_date = Carbon::now()->addYears($change_plan->interval_count);
            } else if ($change_plan->interval == 'week') {
                $end_date = Carbon::now()->addWeeks($change_plan->interval_count);
            } else if ($change_plan->interval == 'day') {
                $end_date = Carbon::now()->addDays($change_plan->interval_count);
            }
            $created_subscription = PaypalSubscription::create(['user_id' => $user->id, 'payment_id' => 'by admin', 'user_name' => $user->name, 'package_id' => $change_plan->id, 'price' => $change_plan->amount, 'status' => 1, 'method' => 'by Admin', 'subscription_from' => $current_date, 'subscription_to' => $end_date]);
            return back()->with('added', __('User subscription has been changed!'));
        }
        return back()->with('error', __('Some issue to change this user subscription'));

    }

}
