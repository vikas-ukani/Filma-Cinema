<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Package;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Stripe\Customer;
use Stripe\Stripe;
use App\Config;
use Braintree_Gateway;
use Illuminate\Support\Facades\Mail;
use App\PaypalSubscription;
use Validator;
use Stripe\Subscription;
use App\Multiplescreen;
use App\Mail\SendInvoiceMailable;
use App\ManualPaymentMethod;
use App\ManualPayment;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class PaymentController extends Controller
{ 
  public function stripeprofile(Request $request)
  {
    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();
    $sub_id = $request->transaction;
    Stripe::setApiKey(env('STRIPE_SECRET'));
    $txn = Subscription::retrieve($sub_id);
    $input = $request->all();
    $plan = Package::where('plan_id',$txn->plan->id)->first();
    $auth->update([
      'stripe_id' => $input['customer'] != null ? $input['customer'] : $auth->stripe_id,
      'card_brand' => $input['type'] != null ? $input['type'] : $auth->card_brand,
      'card_last_four' => $input['card'] != null ? $input['card'] : $auth->card_last_four
    ]);
    $auth->save();
    $created_subscription = $auth->subscriptions()->create([
      'user_id' => $auth->id,
      'name' => $plan->name,
      'stripe_id' => $txn->id,
      'stripe_plan' => $txn->plan->id,
      'quantity' => $txn->quantity,
      'amount' => $txn->plan->amount/100,
      'trial_ends_at' => $txn->trial_end != null ? Carbon::createFromTimestampUTC($txn->trial_end) : null,
      'ends_at' => null,      
      'subscription_from' => Carbon::createFromTimestamp($txn->current_period_start),
      'subscription_to' => Carbon::createFromTimestamp($txn->current_period_end)
    ]);
    if ($created_subscription) {
      $screen = $plan->screens;
      if($screen > 0){
        $multiplescreen = Multiplescreen::where('user_id',$auth->id)->first();
         if(isset($multiplescreen)){
            $multiplescreen->delete();
        }
        $multiplescreen = Multiplescreen::create([
          'pkg_id' => $plan->id,
          'user_id' => $auth->id,
          'screen1' => $screen >= 1 ? $auth->name :  null,
          'screen2' => $screen >= 2 ? 'screen2' :  null,
          'screen3' => $screen >= 3 ? 'screen3' :  null,
          'screen4' => $screen >= 4 ? 'screen4' :  null
        ]);
      }
      try{
        Mail::to($auth->email)->send(new SendInvoiceMailable());
      }
      catch(\Swift_TransportException $e){
        return response()->json(array('message' => 'Successful but mail not send', 'subscription' => $created_subscription), 200);  
      }
      return response()->json(array('message' => 'Successful', 'subscription' => $created_subscription), 200);
    }
    else{ 
      return response()->json(array('auth' =>$auth, 'message' => 'error in storing data'), 200);
    }
  }
  
  public function stripeupdate(Request $request,$id, $value)
  {
    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

      $auth = Auth::user();
      if($a = $auth->subscribed($id)){
        $flag = $auth->subscription($id)->cancelled();
        if((int)$flag != $value){        
          return response()->json($value, 200);
        }
        elseif($value == 1){
          $auth->subscription($id)->resume();
          return response()->json($value, 200);
        }
       elseif($value == 0){
        $auth->subscription($id)->cancel();
        return response()->json($value, 200);
       }
       else{
        return response()->json('invalid value', 400);
       }
     }        
     return response()->json('Not subscribed to this plan', 400);
  }

  public function paypalupdate(Request $request,$id,$value)
  {
    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();
    $data = $auth->paypal_subscriptions->where('payment_id', $id)->first();
    if($data){
      $data->status = $value;
      $data->save();
      return response()->json($data->status, 200);
    }
    else{
      return response()->json('Not subscribed to this plan', 400);
    }
  }

  public function stripedetail(Request $request)
  {
    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();
    if($auth){
      $stripekey =  env('STRIPE_KEY') ? env('STRIPE_KEY') : null;
      $stripepass = env('STRIPE_SECRET') ? env('STRIPE_SECRET') : null;
      $paystackkey = env('PAYSTACK_PUBLIC_KEY') ? env('PAYSTACK_PUBLIC_KEY') : null;
      $razorkey = env('RAZOR_PAY_KEY') ? env('RAZOR_PAY_KEY') : null;
      $razorpass = env('RAZOR_PAY_SECRET') ? env('RAZOR_PAY_SECRET') : null;
      $paytmkey = env('PAYTM_MID') ? env('PAYTM_MID') : null;
      $paytmpass = env('PAYTM_MERCHANT_KEY') ? env('PAYTM_MERCHANT_KEY') : null;
      $imapikey = env('IM_API_KEY') ? env('IM_API_KEY') : NULL;
      $imauthtoken = env('IM_AUTH_TOKEN') ? env('IM_AUTH_TOKEN') : NULL;
      $imurl = env('IM_URL') ? env('IM_URL') : NULL;
      $paypalClientId = env('PAYPAL_CLIENT_ID') ? env('PAYPAL_CLIENT_ID') : NULL;
      $paypalSecretId = env('PAYPAL_SECRET_ID') ? env('PAYPAL_SECRET_ID') : NULL;
      $paypalMode = env('PAYPAL_MODE') ? env('PAYPAL_MODE') : NULL;
      $cashfreeAppID = env('CASHFREE_APP_ID') ? env('CASHFREE_APP_ID') : NULL;
      $cashfreeSecrectID = env('CASHFREE_SECRET_ID') ? env('CASHFREE_SECRET_ID') : NULL;
      $cashfreeApiEndUrl = env('CASHFREE_API_END_URL') ? env('CASHFREE_API_END_URL') : NULL;
      $payhereAppCode = env('PAYHERE_BUISNESS_APP_CODE') ? env('PAYHERE_BUISNESS_APP_CODE') : NULL;
      $payhereAppSecret = env('PAYHERE_APP_SECRET') ? env('PAYHERE_APP_SECRET') : NULL;
      $payhereMerchantId = env('PAYHERE_MERCHANT_ID') ? env('PAYHERE_MERCHANT_ID') : NULL;
      $payhereMode = env('PAYHERE_MODE') ? env('PAYHERE_MODE') : NULL;
      $ravePublicKey = env('RAVE_PUBLIC_KEY') ? env('RAVE_PUBLIC_KEY') : NULL;
      $raveSecretKey = env('RAVE_SECRET_KEY') ? env('RAVE_SECRET_KEY') : NULL; 
      $raveCountry = env('RAVE_COUNTRY') ? env('RAVE_COUNTRY') : NULL;
      $raveSecretHash = env('RAVE_SECRET_HASH') ? env('RAVE_SECRET_HASH') : NULL;
      $ravePrefix = env('RAVE_PREFIX') ? env('RAVE_PREFIX') : NULL;
      $raveLogo = env('RAVE_LOGO') ? env('RAVE_LOGO') : NULL;
      return response()->json(array('key' => $stripekey, 'pass' => $stripepass, 'paystack' => $paystackkey, 'razorkey' => $razorkey, 'razorpass' => $razorpass,'paytmkey' => $paytmkey, 'paytmpass' => $paytmpass,'imapikey'=>$imapikey,'imauthtoken'=>$imauthtoken,'imurl' => $imurl,'paypalClientId' => $paypalClientId, 'paypalSecretId' => $paypalSecretId,'paypalMode' => $paypalMode,'cashfreeAppID' => $cashfreeAppID,'cashfreeSecrectID' => $cashfreeSecrectID,'cashfreeApiEndUrl' => $cashfreeApiEndUrl,'payhereAppCode' => $payhereAppCode,'payhereAppSecret' => $payhereAppSecret,'payhereMerchantId' => $payhereMerchantId,'payhereMode'=>$payhereMode,'ravePublicKey' => $ravePublicKey,'raveSecretKey' => $raveSecretKey,'raveCountry' => $raveCountry,'raveSecretHash' => $raveSecretHash,'ravePrefix' => $ravePrefix,'raveLogo' => $raveLogo), 200);
    }
    return response()->json('please login first', 400);
  }

  public function btpayment(Request $request)
  { 
    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $gateway = $this->brainConfig();
    $customer = Auth::user();
    $currency = Config::findOrFail(1)->currency_code;
    $account = env('BTREE_MERCHANT_ACCOUNT_ID');
    $acc = $gateway->merchantAccount()->find($account);
    if(isset($acc) && ($acc->currencyIsoCode == $currency)){
      $result = $gateway->transaction()->sale([
        'amount' =>  $request->amount,
        'paymentMethodNonce' => $request->nonce,
        'options' => [
          'submitForSettlement' => True
         ]
      ]);    
      if ($result->success || !is_null($result->transaction)) {
        $txn = $result->transaction;
        $paypal = null;
        if($txn->paymentInstrumentType == 'paypal_account'){
          $paypal = $txn->paypal;
        }
        $plan = Package::findOrFail($request->plan_id);
        $user_email = $customer->email;
        $com_email = Config::findOrFail(1)->w_email;
        $current_date = Carbon::now();
        $end_date = null;

        if ($plan->interval == 'month') {
          $end_date = Carbon::now()->addMonths($plan->interval_count);
        } else if ($plan->interval == 'year') {
          $end_date = Carbon::now()->addYears($plan->interval_count);
        } else if ($plan->interval == 'week') {
          $end_date = Carbon::now()->addWeeks($plan->interval_count);
        } else if ($plan->interval == 'day') {
          $end_date = Carbon::now()->addDays($plan->interval_count);
        }

        $created_subscription = PaypalSubscription::create([
          'user_id'    => $customer->id,
          'payment_id' => isset($paypal) ? $paypal['paymentId'] : $txn->id,
          'user_name' => $customer->name,
          'package_id' => $request->plan_id,
          'price'      => $txn->amount,
          'status'     => '1',
          'method'     => isset($paypal) != null ? 'paypal' : 'braintree',
          'subscription_from' => $current_date,
          'subscription_to' => $end_date
        ]);
        if ($created_subscription) {
          $screen = $plan->screens;
          if($screen > 0){
            $multiplescreen = Multiplescreen::where('user_id',$customer->id)->first();
           if(isset($multiplescreen)){
                $multiplescreen->delete();
            }
            $multiplescreen = Multiplescreen::create([
              'pkg_id' => $plan->id,
              'user_id' => $customer->id,
              'screen1' => $screen >= 1 ? $customer->name :  null,
              'screen2' => $screen >= 2 ? 'screen2' :  null,
              'screen3' => $screen >= 3 ? 'screen3' :  null,
              'screen4' => $screen >= 4 ? 'screen4' :  null
            ]);
          }
          try{
            Mail::send('user.invoice', ['paypal_sub' => $created_subscription, 'invoice' => null], function($message) use ($com_email, $user_email) {
              $message->from($com_email)->to($user_email)->subject('Invoice');
            });
          }catch(\Swift_TransportException $e){
            return response()->json(array('message' => 'Successful but mail not send', 'subscription' => $created_subscription), 200);          
          }
        }     
        return response()->json(array('message' => 'Successful', 'subscription' => $created_subscription), 200);
      } else {      
        return response()->json('Payment error', 401);
      }
    }
    else {      
        return response()->json('Currency Not Supported', 401);
    }
  }


  public function bttoken(Request $request)
  {
    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $customer = Auth::user();       
    $gateway = $this->brainConfig();
    if(!$customer->braintree_id){
      $response = $gateway->customer()->create([
          'firstName' => Auth::user()->name,
          'email' => Auth::user()->email
       ]);
      if( $response->success) {
        $customer->braintree_id = $response->customer->id;
        $customer->save();
      }
      else{
        return response()->json('error in token', 401);
      }
    }
    $client = $gateway->clientToken()->generate([
      "customerId" =>  $customer->braintree_id
    ]);
    return response()->json(array('client' => $client), 200);
  }

  public function brainConfig()
  {
    

    return $gateway = new Braintree_Gateway([
        'environment' => env('BTREE_ENVIRONMENT'),
        'merchantId' => env('BTREE_MERCHANT_ID'),
        'publicKey' => env('BTREE_PUBLIC_KEY'),
         'privateKey' => env('BTREE_PRIVATE_KEY')
    ]);
 }
  

  public function paystack(Request $request)
  {
    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $payment = $this->payment_save($request['plan_id'], $request['amount'], $request['reference'], 1, 'paystack');
    return response()->json($payment);
  }

  public function pay_store(Request $request)
  {
    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $payment = $this->payment_save($request['plan_id'], $request['amount'], $request['reference'], $request['status'], $request['method']);
    return response()->json($payment);
  }

  public function manual_payment_list(Request $request){
    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }
    $manualPayment = ManualPaymentMethod ::where('status', 1)->get()->transform(function($mp){
        
      $mp['thumb_path'] = url('/images/manualpayment');
      
      return $mp;
      
    });
    return response()->json(array('manualPayment' => $manualPayment ), 200); 
  }

  public function manual_payment_gateway(Request $request){
    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }
    
     $validator = Validator::make($request->all(), [
            'proof' => 'required',
            'amount' => 'required',
            'plan_id' => 'required',
            'reference' => 'required',
            'method' => 'required'
        ]);

        if ($validator->fails()) {
             $errors = $validator->errors();

            if ($errors->first('amount')) {
                return response()->json(['msg' => $errors->first('amount'), 'status' => 'fail'],422);
            }
            if ($errors->first('proof')) {
                return response()->json(['msg' => $errors->first('proof'), 'status' => 'fail'],422);
            }
            if ($errors->first('plan_id')) {
                return response()->json(['msg' => $errors->first('plan_id'), 'status' => 'fail'],422);
            }
            if ($errors->first('reference')) {
                return response()->json(['msg' => $errors->first('reference'), 'status' => 'fail'],422);
            }if ($errors->first('method')) {
                return response()->json(['msg' => $errors->first('method'), 'status' => 'fail'],422);
            }
           
        }
    $auth = Auth::user();
    $plan = Package::find($request->plan_id);
    $current_date = Carbon::now();
    $end_date = null;

    if ($plan->interval == 'month') {
      $end_date = Carbon::now()->addMonths($plan->interval_count);
    } else if ($plan->interval == 'year') {
      $end_date = Carbon::now()->addYears($plan->interval_count);
    } else if ($plan->interval == 'week') {
      $end_date = Carbon::now()->addWeeks($plan->interval_count);
    } else if ($plan->interval == 'day') {
      $end_date = Carbon::now()->addDays($plan->interval_count);
    }

    if ($file = $request->file('proof')) {
      $name = "manual_" . time() . $file->getClientOriginalName();
      $file->move('images/recipt', $request->proof);

    }else{
        $name = NULL;
    }
    // return $request;
    $mpayment = ManualPayment::create([
      'package_id' => $request->plan_id,
      'price' => $request->amount,
      'payment_id' => $request->reference,
      'status' => 0,
      'method' => $request->method,
      'user_id' => $auth->id,
      'user_name' => $auth->name,
      'file' => $name,
      'subscription_from' => $current_date,
      'subscription_to' => $end_date,
    ]);

    return $mpayment;
    return response()->json($mpayment);
  }

  public function freeSubscription(Request $request){

    $data  = new MainController;
    $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $validator = Validator::make($request->all(), [
      'amount' => 'required',
      'plan_id' => 'required',
      'reference' => 'required',
      'method' => 'required',
      'status' => 'required'
  ]);

  if ($validator->fails()) {
       $errors = $validator->errors();

      if ($errors->first('amount')) {
          return response()->json(['msg' => $errors->first('amount'), 'status' => 'fail'],422);
      }
      if ($errors->first('status')) {
          return response()->json(['msg' => $errors->first('status'), 'status' => 'fail'],422);
      }
      if ($errors->first('plan_id')) {
          return response()->json(['msg' => $errors->first('plan_id'), 'status' => 'fail'],422);
      }
      if ($errors->first('reference')) {
          return response()->json(['msg' => $errors->first('reference'), 'status' => 'fail'],422);
      }if ($errors->first('method')) {
          return response()->json(['msg' => $errors->first('method'), 'status' => 'fail'],422);
      }
     
  }

    $payment = $this->payment_save($request['plan_id'], $request['amount'], $request['reference'], $request['status'], $request['method']);
    return response()->json($payment);
  }

  public function payment_save($planid, $amount, $txnid, $status, $method)
  {
    

    $auth = Auth::user();
    $plan = Package::findorfail($planid);
    $user_email = $auth->email;
    $com_email = Config::findOrFail(1)->w_email;
    $current_date = Carbon::now();
    $end_date = null;

    if ($plan->interval == 'month') {
      $end_date = Carbon::now()->addMonths($plan->interval_count);
    } else if ($plan->interval == 'year') {
      $end_date = Carbon::now()->addYears($plan->interval_count);
    } else if ($plan->interval == 'week') {
      $end_date = Carbon::now()->addWeeks($plan->interval_count);
    } else if ($plan->interval == 'day') {
      $end_date = Carbon::now()->addDays($plan->interval_count);
    }

    $created_subscription = PaypalSubscription::create([
      'user_id'    => $auth->id,
      'payment_id' => $txnid,
      'user_name' => $auth->name,
      'package_id' => $plan->id,
      //'currency' => $plan->currency,
      'price'      => $amount,
      'status'     => $status,
      'method'     => $method,
      'subscription_from' => $current_date,
      'subscription_to' => $end_date
    ]);
    if ($created_subscription) {
      $screen = $plan->screens;
      if($screen > 0){
        $multiplescreen = Multiplescreen::where('user_id',$auth->id)->first();
        if(isset($multiplescreen)){
            $multiplescreen->delete();
        }
        $multiplescreen = Multiplescreen::create([
          'pkg_id' => $plan->id,
          'user_id' => $auth->id,
          'screen1' => $screen >= 1 ? $auth->name :  null,
          'screen2' => $screen >= 2 ? 'screen2' :  null,
          'screen3' => $screen >= 3 ? 'screen3' :  null,
          'screen4' => $screen >= 4 ? 'screen4' :  null       
        ]);
      }
      try{
      Mail::send('user.invoice', ['paypal_sub' => $created_subscription, 'invoice' => null], function($message) use ($com_email, $user_email) {
        $message->from($com_email)->to($user_email)->subject('Invoice');
      });
      }catch(\Swift_TransportException $e){        
        return array('message' => 'Successful but mail not send', 'subscription' => $created_subscription);          
      }   
      return array('message' => 'Successful', 'subscription' => $created_subscription);
    } else {            
      return 'error in storing data';
    }
  }

  public function invoicedownload(Request $request,$id){
   $data  = new MainController;
   $secretData = $data->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $invoice = PaypalSubscription::find($id);
    $stylesheet = file_get_contents('css/bootstrap.min.css');
    $pdf = Pdf::loadView('user.download', compact('invoice'), [], 
      [ 
        'title' => 'Invoice', 
        'orientation' => 'L',
        'images' => true
      ]
    );

    return $pdf->download('invoice.pdf');
  }

 

}

   