<?php

namespace App\Http\Controllers\Auth;

use App\Affilate;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;
use Session;
use Auth;
use App\Mail\verifyEmail;
use Illuminate\Support\Str;
use App\Mail\WelcomeUser;
use App\Config;
use Carbon\Carbon;
use Notification;
use App\Menu;
use App\Notifications\MyNotification;
use App\PaypalSubscription;
use Illuminate\Http\Request;


  /*==========================================
    =            Author: Media City            =
    Author URI: https://mediacity.co.in
    =            Author: Media City            =
    =            Copyright (c) 2022            =
    ==========================================*/

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->config = Config::first();
    }

    public function showRegistrationForm()
    {   
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    
    public function register(Request $request){

      if($this->config->captcha == 1){

           $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'g-recaptcha-response' => 'required|captcha',
            ], [
                'name.required' => __('Please Choose a name'),
                'email.required' => __('Email is required !'),
                'email.email' => __('Email must be in valid format'),
                'email.unique' => __('This email is already taken, Please choose another'),
                'password.required' => __('Password cannot be empty'),
                'password.confirmed' => __("Password doesn't match"),
                'password.min' => __('Password length must be greater than 6')
            ]);
          }

      else{

       $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => __('Please Choose a name'),
            'email.required' => __('Email is required !'),
            'email.email' => __('Email must be in valid format'),
            'email.unique' => __('This email is already taken, Please choose another'),
            'password.required' => __('Password cannot be empty'),
            'password.confirmed' => __("Password doesn't match"),
            'password.min' => __('Password length must be greater than 6')
        ]);
      }

      $af_system = Affilate::first();

      $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'is_admin' => 0,
            'status' => 1,
            'password' => bcrypt($request['password']),
            'verifyToken' => Str::random(40),
            'refered_from' => $af_system && $af_system->enable_affilate == '1'  ? $request['refer_code'] : NULL
        ]);

      $user->assignRole('User');
      
      if($request->refer_code != NULL){
        $this->refer($request,$user);
      }
        
     
     

      if($this->config->verify_email == 1){
 
          $user->status = 0;
          $user->save();

          try{

            Mail::to($user['email'])->send(new verifyEmail($user));

            return redirect()->route('login')->with('success',__('Verification Email has been sent to your email'));

          }catch(\Exception $e){

            return redirect()->route('login')->with('success',$e->getMessage());

          }

          

      }else{
            Auth::login($user);
            return redirect('/');
      }

      if($this->config->wel_eml == 1 && $this->config->verify_email == 0 ){
         if($this->config->free_sub == 1){
        
              $this->freesubscribe($user);
              Auth::login($user);
              if($this->config->remove_landing_page == 1){
              $menu = Menu::all();
                  return redirect()->route('home',$menu[0]->slug);
                }else{
                  return redirect('/');
                }
          
          }
        try{
          Mail::to($data['email'])->send(new WelcomeUser($user));
        }catch(\Exception $e){
            \Log::error('Mail can\'t sent to user'.$user->name.' at time of register');
        }

        Auth::login($user);
        return redirect('/');


      }else{
        Auth::login($user);
        return redirect('/');
      }
      if($this->config->wel_eml == 0 && $this->config->verify_email == 0){
         
          if($this->config->free_sub == 1){
              $this->freesubscribe($user);
           
          }
          Auth::login($user);
          if($this->config->remove_landing_page == 1){
          $menu = Menu::all();
                  return redirect()->route('home',$menu[0]->slug);
                }else{
                  return redirect('/');
                }
       
      }
      
     
    }



    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */





public function freesubscribe($thisuser){
    $start=Carbon::now();
    $end=$start->addDays($this->config->free_days);
    $payment_id=mt_rand(10000000000000, 99999999999999);
    $created_subscription = PaypalSubscription::create([
        'user_id' => $thisuser->id,
        'payment_id' => $payment_id,
        'user_name' => $thisuser->name,
        'package_id' => 0,
        'price' => 0,
        'status' => 1,
        'method' => 'free',
        'subscription_from' => Carbon::now(),
        'subscription_to' => $end
    ]);

    $ps=PaypalSubscription::where('user_id',$thisuser->id)->first();
    $to= Str::substr($ps->subscription_to,0, 10);
    $from= Str::substr($ps->subscription_from,0, 10);
    $title=$this->config->free_days.' Days '.__('staticwords.freetrial');
    $desc=__('staticwords.freetrialtext').' '.$from.' to '.$to;
    $movie_id=NULL;
    $tvid=NULL;
    $user=$thisuser->id;
    User::find($thisuser->id)->notify(new MyNotification($title,$desc,$movie_id,$tvid,$user));


}


public function sendEmailDone($email, $verifyToken){
     $user = User::where(['email' => $email, 'verifyToken' => $verifyToken])->first();

    if($user){
        User::where(['email' => $email, 'verifyToken' => $verifyToken])->update(['status'=>'1','verifyToken'=>NULL]);
        Session::flash('success', __('Email Verification Successfull'));

        Mail::to($user->email)->send(new WelcomeUser($user));
         if($this->config->free_sub == 1){
              $this->freesubscribe($user);
          
          }
        return redirect()->route('login');
    }else{
        return 'user not found';
    }
}

  public function refer(Request $request,$user){

    $af_system = Affilate::first();

    if($af_system && $af_system->enable_affilate == '1'){

        $findreferal = User::firstWhere('refer_code',$request->refer_code);

        if(!$findreferal){

            return back()->withInput()->withErrors([
                'refercode' =>__('Refer code is invalid !')
            ]);

        }

    }
   
    if($af_system && $af_system->enable_affilate == '1'){



      $findreferal->getReferals()->create([
          'log' => 'Refer successfull',
          'refer_user_id' => $user->id,
          'user_id' => $findreferal->id,
          'amount' => $af_system->refer_amount,
          'procces' => $af_system->enable_purchase == 1 ? 0 : 1
      ]);

      
      if(!$findreferal->wallet){
          $w = $findreferal->wallet()->create([
              'balance' => $af_system->refer_amount,
              'status'  =>  '1',
          ]);

          $w->wallethistory()->create([
              'type' => 'Credit',
              'log' => 'Referal bonus',
              'amount' => $af_system->refer_amount,
              'txn_id' => str_random(8),
              'expire_at' => date("Y-m-d", strtotime(date('Y-m-d').'+365 days'))
          ]);

      }

      if(isset($findreferal->wallet) && $findreferal->wallet->status == 1){

          $findreferal->wallet()->update([
              'balance' => $findreferal->wallet->balance + $af_system->refer_amount
          ]);

          $findreferal->wallet->wallethistory()->create([
              'type' => 'Credit',
              'log' => 'Referal bonus',
              'amount' => $af_system->refer_amount,
              'txn_id' => str_random(8),
              'expire_at' => date("Y-m-d", strtotime(date('Y-m-d').'+365 days'))
          ]);

      }
      
    }

  }

}

