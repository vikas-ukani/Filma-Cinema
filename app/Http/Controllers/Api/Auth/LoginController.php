<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;  
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use App\User;
use App\Config;
use Mail;
use Stripe\Stripe;
use Hash;
use App\Multiplescreen;
use App\PaypalSubscription;
use Illuminate\Support\Carbon;

class LoginController extends Controller
{

    use IssueTokenTrait;

    private $client;

    public function __construct(){
        $this->client = Client::find(2);
    }

    public function login(Request $request){

        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        
        $authUser = User::where('email', $request->email)->first();
        if(isset($authUser) && $authUser->is_blocked == 1){
            return response()->json('Blocked User', 401); 
        }
        else{
            if(isset($authUser) && $authUser != NULL){
                if($authUser->status == 0){
                   return response()->json('Please Verify your mail !', 201);
                }
                else{
                    return $this->issueToken($request, 'password');
                }
            }else{
                 $response = ["message" => "Unauthorized !"];
                 return response()->json($response, 401);
            }
            
            
        }

    }

    public function sociallogin(Request $request){

        $this->validate($request, [
            'email' => 'required',
            'name' => 'required',
            'code' => 'required',
            'password' => '',
            'provider' => 'required'
        ]);

        $providerField = "{$request->provider}_id";

        $authUser = User::where('email', $request->email)->orwhere($providerField,$request->code)->first();
        if($authUser){
            $authUser->{$providerField} = $request->code;
            $authUser->name = $request->name;
            $authUser->email = $request->email;
            $authUser->save();
            if(isset($authUser) &&  $authUser->is_blocked == 1){
                return response()->json('Blocked User', 401); 
            }
             else{
               if (Hash::check('password', $authUser->password)) {

                    return $response = $this->issueToken($request,'password');

            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }

            }
        }
        else{
            $user = User::create([
                'name' =>  request('name'),
                'email' => request('email'),
                'password' => Hash::make($request->password !='' ? $request->password : 'password'),
                $providerField => request('code'),
                'is_blocked' => '0',
                'status'=>'1'
            ]);

            $config = Config::first();
            if($config->free_sub==1){
              $this->freesubscribe($user);
            }
            
            return $this->issueToken($request, 'password');
        }
    }

    
    public function refresh(Request $request){
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        return $this->issueToken($request, 'refresh_token');
    }
    
    public function forgotApi(Request $request)
    { 
        $user = User::whereEmail($request->email)->first();
        if($user){
            $uni_col = array(User::pluck('code'));
            do {
              $code = str_random(5);
            } while (in_array($code, $uni_col));            
            try{
                $config = Config::findOrFail(1);
                $logo = $config->logo;
                $email = $config->w_email;
                $company = $config->title;
                Mail::send('forgotemail', ['code' => $code, 'logo' => $logo, 'company'=>$company], function($message) use ($user, $email) {
                    $message->from($email)->to($user->email)->subject('Reset Password Code');
                });
                $user->code = $code;
                $user->save();
                return response()->json('ok', 200);
            }
            catch(\Swift_TransportException $e){
                return response()->json('Mail Sending Error', 400);
            }
        }
        else{          
            return response()->json('user not found', 401);  
        }
    }

    public function verifyApi(Request $request)
    { 
        if( ! $request->code || ! $request->email)
        {
            return response()->json('email and code required', 449);
        }

        $user = User::whereEmail($request->email)->whereCode($request->code)->first();

        if ( ! $user)
        {            
            return response()->json('not found', 401);
        }
        else{
            $user->code = null;
            $user->save();
            return response()->json('ok', 200);
        }
    }

    public function resetApi(Request $request)
    { 
        $this->validate($request, ['password' => 'required|confirmed|min:6']);

        $user = User::whereEmail($request->email)->first();

        if($user){

            $user->update(['password' => bcrypt($request->password)]);

            $user->save(); 
            
            return response()->json('ok', 200);
        }
        else{          
            return response()->json('not found', 401);
        }
    }

    public function logoutApi(Request $request)
    { 

        $accessToken = Auth::user()->token();
        if($accessToken){
            $activesubsription = PaypalSubscription::where('user_id', Auth::user()->id)->where('status', '=', 1)->orderBy('created_at', 'desc')->first();
            if($activesubsription){
                if(($request->count != '' || $request->count != NULL) && ($request->screen != '' || $request->screen != NULL)){
                    $getscreens = Multiplescreen::where('user_id', '=', Auth::user()->id)->first();
                    if(isset($getscreens)){
                        $macaddress = $request->macaddress;
                        $device_count = 'device_mac_'.$request->count;
                        $screen_count = 'screen_'.$request->count.'_used';
                       
                        $query = $getscreens->where('id',$getscreens->id)->update([$screen_count => 'NO',$device_count => NULL]);

                    }
                }
                
            }
            
        }
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();
        return response()->json(null, 200);
    }


    public function freesubscribe($thisuser){
  
        $config=Config::first();
        $start=Carbon::now();
        $end=$start->addDays($config->free_days);
        $payment_id=mt_rand(10000000000000, 99999999999999);
        $created_subscription = PaypalSubscription::create([
            'user_id' => $thisuser->id,
            'payment_id' => $payment_id,
            'user_name' => $thisuser->name,
            'package_id' => 100,
            'price' => 0,
            'status' => 1,
            'method' => 'free',
            'subscription_from' => Carbon::now(),
            'subscription_to' => $end
        ]);
    
    }
    
}
