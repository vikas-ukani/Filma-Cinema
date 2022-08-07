<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\PaypalSubscription;
use App\Package;
use App\Menu;
use App\User;
use App\Config;
use App\Multiplescreen;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str as Str;
use Stripe\Stripe;
use Stripe\Customer;
use Notification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Notifications\MyNotification;
use App\Button;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

  /*==========================================
    =            Author: Media City            =
    Author URI: https://mediacity.co.in
    =            Author: Media City            =
    =            Copyright (c) 2022            =
    ==========================================*/
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    public function __construct(){
      
      $this->config = Config::first();
      $this->button = Button::first();
      $this->middleware('guest')->except('logout');

    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request){

        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password],$request->remember)) {


            if($this->config->verify_email == 1){


              if(Auth::user()->verifyToken != null){
                     Auth::logout();
                    return back()->withErrors(['email' => __('Please verify your email !')]);
              } 

            }

            if(Auth::user()->status == 0){
                 Auth::logout();
                 return back()->withErrors(['email' =>__('Your account is not active !')]);
            }

            if(Auth::user()->is_blocked == 1){
               Auth::logout();
              return back()->withErrors(['email' =>__('You Do Not Have Access to This Site Anymore. You are Blocked.')]);
            }

            

            session()->put('user',['email' => Auth::user()->email, 'name' => Auth::user()->name]);

            return $this->afterLoginProcess(Auth::user());

          

        }else{
           return back()->withErrors(['email' => __('Email or password is invalid !')]);
        }

    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function afterLoginProcess($auth)
    {
      

        if ($this->config->free_sub==1) {

        $ps=PaypalSubscription::where('user_id',$auth->id)->first();

        if ($auth->is_admin!=1) {          
          if (!isset($ps) ) {
            $start=Carbon::now();
            $end=$start->addDays($this->config->free_days);
            $payment_id=mt_rand(10000000000000, 99999999999999);
            $subscribed = 1;
            $created_subscription = PaypalSubscription::create([
              'user_id' => $auth->id,
              'payment_id' => $payment_id,
              'user_name' => $auth->name,
              'package_id' => 0,
              'price' => 0,
              'status' => 1,
              'method' => 'free',
              'subscription_from' => Carbon::now(),
              'subscription_to' => $end
            ]);
            $ps=PaypalSubscription::where('user_id',auth()->user()->id)->first();
            $to= Str::substr($ps->subscription_to,0, 10);
            $from= Str::substr($ps->subscription_from,0, 10);
            $desc=__('staticwords.freetrialtext').' '.$from.' to '.$to;
            $title=$this->config->free_days.' Days '.__('staticwords.freetrial');
          
            $movie_id=NULL;
            $tvid=NULL;
            $user=$auth->id;
            User::findOrFail($auth->id)->notify(new MyNotification($title,$desc,$movie_id,$tvid,$user));
          }
        }
      }
      $subscribed = null;
      if ($auth->is_admin == 1 ||  $auth->is_assistant == 1) {// do your margic here
         $subscribed = 1;
         return redirect('/admin');
       }
       else if(Auth::user()->is_blocked == 1){
            Auth::logout();
            return back()->with('deleted',__('You Do Not Have Access to This Site Anymore. You are Blocked.'));
       } 
       else
      {


        session()->put('user',['email' => Auth::user()->email, 'name' => Auth::user()->name]);

      
        $current_date = Carbon::now()->toDateString();
        Stripe::setApiKey(env('STRIPE_SECRET'));
        if ($auth->stripe_id != null) {
          $customer = Customer::retrieve($auth->stripe_id);
        }
        $paypal = PaypalSubscription::where('user_id',$auth->id)->where('status','=',1)->where('method','!=','free')->orderBy('created_at','desc')->first(); 

        $paypal_for_free = PaypalSubscription::where('user_id',$auth->id)->where('status','=',1)->where('method','free')->orderBy('created_at','desc')->first(); 

        if (isset($customer)) {         
          $alldata = $auth->subscriptions;
          $data = $alldata->last();      
        } 
        if (isset($paypal) && $paypal != null && $paypal->count() >0) {
          $last = $paypal;
        } 
        $stripedate = isset($data) ? $data->created_at : null;
        $paydate = isset($last) ? $last->created_at : null;
        if($stripedate > $paydate){
         
          if($auth->subscribed($data->name) && date($current_date) <= date($data->subscription_to)){
            $subscribed = 1;
            if($data->ends_at != null){
              return redirect('/')->with('deleted', __('Please resume your subscription!'));
            }  
            else{
              if(isset($data->stripe_plan) && $data->stripe_plan != NULL){
                $planmenus= DB::table('package_menu')->where('package_id', $data->stripe_plan)->get();
                if(isset($planmenus)){ 
                   foreach ($planmenus as $key => $value) {
                     $menus[]=$value->menu_id;
                   }
                 }
                if(isset($menus)){ 
                  $nav_menus=Menu::whereIn('id',$menus)->get();
                   foreach ($nav_menus as $nav => $menus) {
                     return redirect($menus->slug);
                   }
                 }               
              }else{
                if($this->config->remove_landing_page == 1){
                  return redirect('account');
                }else{
                  return redirect('/');
                }
              }
                   
            }
          }else {
              
            return redirect('/')->with('deleted', __('Your subscription has been expired!'));
          }
        }
        elseif($stripedate < $paydate){
        
          if (date($current_date) <= date($last->subscription_to)){


             //Login limit check

            if(isset($this->button->multiplescreen) && $this->button->multiplescreen == 1){

              $current_screen = Multiplescreen::where('user_id',$auth->id)->first();

              if(!isset($current_screen)){
                return redirect()->route('manageprofile',Auth::user()->id);
              }
           
              $macaddress = $_SERVER['REMOTE_ADDR'];

              $checkscreen = array();

              if($current_screen->package->screens == 1){
                if($current_screen->screen_1_used == 'YES' && $macaddress != $current_screen->device_mac_1){

                  array_push($checkscreen,1);


                }else{
                  array_push($checkscreen,0);
                  if($macaddress == $current_screen->device_mac_1){
                    Session::put('nickname',$current_screen->screen1);
                  }
                }

              }

              if($current_screen->package->screens == 2){

                if($current_screen->screen_1_used == 'YES' && $macaddress != $current_screen->device_mac_1){

                  array_push($checkscreen,1);


                }else{
                  array_push($checkscreen,0);
                  if($macaddress == $current_screen->device_mac_1){
                    Session::put('nickname',$current_screen->screen1);
                  }
                }

                if($current_screen->screen_2_used == 'YES' && $macaddress != $current_screen->device_mac_2){

                  array_push($checkscreen,1);


                }else{
                  array_push($checkscreen,0);
                  if($macaddress == $current_screen->device_mac_2){
                    Session::put('nickname',$current_screen->screen2);
                  }
                }

              }

              if($current_screen->package->screens == 3){

                if($current_screen->screen_1_used == 'YES' && $macaddress != $current_screen->device_mac_1){

                  array_push($checkscreen,1);


                }else{
                  array_push($checkscreen,0);
                  if($macaddress == $current_screen->device_mac_1){
                    Session::put('nickname',$current_screen->screen1);
                  }
                }
                
                if($current_screen->screen_2_used == 'YES' && $macaddress != $current_screen->device_mac_2){

                  array_push($checkscreen,1);


                }else{
                  array_push($checkscreen,0);
                  if($macaddress == $current_screen->device_mac_2){
                    Session::put('nickname',$current_screen->screen2);
                  }
                }

                if($current_screen->screen_3_used == 'YES' && $macaddress != $current_screen->device_mac_3){

                  array_push($checkscreen,1);


                }else{
                  array_push($checkscreen,0);
                  if($macaddress == $current_screen->device_mac_3){
                    Session::put('nickname',$current_screen->screen3);
                  }
                }

              }

              if($current_screen->package->screens == 4){

                if($current_screen->screen_1_used == 'YES' && $macaddress != $current_screen->device_mac_1){

                  array_push($checkscreen,1);


                }else{
                  array_push($checkscreen,0);
                  if($macaddress == $current_screen->device_mac_1){
                    Session::put('nickname',$current_screen->screen1);
                  }
                }
                
                if($current_screen->screen_2_used == 'YES' && $macaddress != $current_screen->device_mac_2){

                  array_push($checkscreen,1);


                }else{
                  array_push($checkscreen,0);
                  if($macaddress == $current_screen->device_mac_2){
                    Session::put('nickname',$current_screen->screen2);
                  }
                }

                if($current_screen->screen_3_used == 'YES' && $macaddress != $current_screen->device_mac_3){

                  array_push($checkscreen,1);

                }else{
                  array_push($checkscreen,0);
                  if($macaddress == $current_screen->device_mac_3){
                    Session::put('nickname',$current_screen->screen3);
                  }
                }

                if($current_screen->screen_4_used == 'YES' && $macaddress != $current_screen->device_mac_4){

                  array_push($checkscreen,1);

                }else{
                  array_push($checkscreen,0);
                  if($macaddress == $current_screen->device_mac_4){
                    Session::put('nickname',$current_screen->screen4);
                  }
                }

              }

              if(!in_array(0, $checkscreen)){
                if(isset($current_screen->package->screens) && $current_screen->package->screens == NULL){
                  
                  return redirect('/account/purchaseplan')->with('deleted',__('You have no subscription please subscribe'));
                }else{
                  Auth::logout();
                  return redirect('/')->with('deleted',__('Device Login limit reached No profile available !'));
                }
                
              }
            }
            
             //End Login limit check
              
            if($last->status == 1) {
              $subscribed = 1;
              if(isset($last->plan['plan_id']) && $last->plan['plan_id'] != NULL){
                $planmenus= DB::table('package_menu')->where('package_id', $last->plan['plan_id'])->get();
                if(isset($planmenus)){ 
                    foreach ($planmenus as $key => $value) {
                    $menus[]=$value->menu_id;
                  }
                }
                if(isset($menus)){ 
                  $nav_menus=Menu::whereIn('id',$menus)->get();
                  foreach ($nav_menus as $nav => $menus) {
                    return redirect($menus->slug);
                  }
                } 
              }else{
                if($this->config->remove_landing_page == 1){
                  return redirect('account');
                }else{
                  return redirect('/');
                }
              }
              
            }

            else{
              return redirect('/')->with('deleted', __('Please resume your subscription!'));
            }                    
          } else {
            $last->status = 0;
            $last->save();
            return redirect('/')->with('deleted', __('Your subscription has been expired!'));
          }
        }
        else
        {
          

          if($this->config->free_sub==1)
          {
            
            if(isset($ps)){
              
              if($ps->method == 'free')
              {
                $menu = Menu::all();
                Session::put('nickname',Auth::user()->name);
                if($this->config->remove_landing_page == 1){
                  return redirect()->route('home',$menu[0]->slug)->with('success',__('You have subscribe now!'));
                }else{
                  return redirect('/')->with('success',__('You have subscribe now!'));
                }
                
              }else{
                if($ps->subscription_to > date('Y-m-d h:i:s')){
                  if(isset($ps->package_id) && $ps->package_id != NULL){
                    $planmenus= DB::table('package_menu')->where('package_id', $ps->package_id)->get();
                    if(isset($planmenus)){ 
                        foreach ($planmenus as $key => $value) {
                        $menus[]=$value->menu_id;
                      }
                    }
                    if(isset($menus)){ 
                      $nav_menus=Menu::whereIn('id',$menus)->get();
                      foreach ($nav_menus as $nav => $menus) {
                        return redirect($menus->slug);
                      }
                    } 
                  }else{
                    if($this->config->remove_landing_page == 1){
                      return redirect('account');
                    }else{
                      return redirect('/');
                    }
                  }
                }else{
                  return redirect('account/purchaseplan')->with('deleted', __('You have no subscription please subscribe'));
                }
              }
            }
          }
          elseif(isset($paypal_for_free))
          { 
           
            
            $menu = Menu::all();
               Session::put('nickname',Auth::user()->name);
              if($this->config->remove_landing_page == 1){
                  return redirect()->route('home',$menu[0]->slug)->with('success',__('You have subscribe now!'));
                }else{
                  return redirect('/')->with('success',__('You have subscribe now!'));
                }
          }
          else
          {
            return redirect('account/purchaseplan')->with('deleted', __('You have no subscription please subscribe'));
          }
          

        }
      }
    }


   
  }
