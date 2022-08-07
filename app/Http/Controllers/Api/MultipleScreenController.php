<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Multiplescreen;
use Auth;
use App\Package;

class MultipleScreenController extends Controller
{

    public function newupdate(Request $request)
    {
        $data  = new MainController;
        $secretData = $data->CheckSecretKey($request);
        
        if($secretData != ''){
          return $secretData;
        }

        $user = Auth::user();
        $macaddress = $request->macaddress;
        $getscreen = Multiplescreen::where('user_id',$user->id)->orderBy('created_at','Desc')->first();
    
        if(isset($macaddress) || $macaddress != null){
            if($macaddress == $getscreen->device_mac_1){
                $getscreen->update(['screen_1_used' => 'NO', 'device_mac_1' => NULL]);
            }elseif($macaddress == $getscreen->device_mac_2){
                $getscreen->update(['screen_2_used' => 'NO', 'device_mac_2' => NULL]);
            }elseif($macaddress == $getscreen->device_mac_3){
                $getscreen->update(['screen_3_used' => 'NO', 'device_mac_3' => NULL]);
            }elseif($macaddress == $getscreen->device_mac_4){
                $getscreen->update(['screen_4_used' => 'NO', 'device_mac_4' => NULL]);
            }
        }
        $screen_count = 'screen_'.$request->count.'_used';
        $device_count = 'device_mac_'.$request->count;
        
        if($request->count){
            $query = $getscreen->where('id',$getscreen->id)->update([$screen_count => 'YES', 'activescreen' => $request->screen,  $device_count => $macaddress]);
            
        }

        if($query){
          
            return response()->json(1, 200);
        }else {
            return response()->json('error', 401);
        }
    }
    public function downloadcounter(Request $request)
    {
        $data  = new MainController;
        $secretData = $data->CheckSecretKey($request);
        
        if($secretData != ''){
          return $secretData;
        }

        $user = Auth::user();
        $screen = 'download_'.$request->count;
        $multiplescreen = Multiplescreen::where('user_id',$user->id)->orderBy('created_at','Desc')->first();
        if($multiplescreen != null){
           $plan = Package::findorfail($multiplescreen->pkg_id);
           if(isset($plan) && $plan->downloadlimit != null && $plan->downloadlimit > 0 && ($plan->downloadlimit/$plan->screens > $multiplescreen->$screen)){

        $query = $multiplescreen->update([$screen => $multiplescreen->$screen+1]);
           }
           else{
             return response()->json('no downloadlimit or downloadlimit exceed', 401);
           }
        }

        if(isset($query)){
            return response()->json(1, 200);
        }else {
            return response()->json('error', 401);
        }
    }

     public function manageprofile(Request $request){

        $data  = new MainController;
        $secretData = $data->CheckSecretKey($request);
        
        if($secretData != ''){
          return $secretData;
        }

        $user = Auth::user();
        $screen = null;
        $screen = Multiplescreen::where('user_id',$user->id)->orderBy('created_at','Desc')->first();
        return response()->json(array('screen' =>$screen), 200);
    }
    public function changescreen(Request $request)
    {
        $data  = new MainController;
        $secretData = $data->CheckSecretKey($request);
        
        if($secretData != ''){
          return $secretData;
        }

        $user = Auth::user();
        $query = Multiplescreen::where('user_id',$user->id)->update(['activescreen' => $request->screen, ]);

        if($query){
            return response()->json(array('ok'), 200);
        }else {
            return response()->json(array('error'), 401);
        }
    }

    public function screenprofile(Request $request)
    {
        $data  = new MainController;
        $secretData = $data->CheckSecretKey($request);
        
        if($secretData != ''){
          return $secretData;
        }

        $user = Auth::user();
        $result = Multiplescreen::where('user_id',$user->id)->first();
        $screentype = $request->type;

        if($request->value != null || $request->value != '')
        {
            $result->{$screentype} = $request->value;
        }
        
        $result->save();

        return response()->json(1, 200);
    }
}
