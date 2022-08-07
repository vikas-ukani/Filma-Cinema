<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Notification;
use Auth;

class NotificationController extends Controller
{

    public function allnotification(Request $request)
    {   
        $data  = new MainController;
        $secretData = $data->CheckSecretKey($request);
            
        if($secretData != ''){
            return $secretData;
        }

        $user = Auth::user();
        $notifications = $user->unreadnotifications;

        if($notifications){
            return response()->json(array('notifications' => $notifications), 200);
        }else {
            return response()->json(array('error'), 401);
        }
    }

    
    public function notificationread(Request $request,$id)
    {   
        $data  = new MainController;
        $secretData = $data->CheckSecretKey($request);
        
        if($secretData != ''){
        return $secretData;
        }

        $userunreadnotification=Auth::user()->unreadNotifications->where('id',$id)->first();
         
        if ($userunreadnotification) {
           $userunreadnotification->markAsRead();
            return response()->json(array('1'), 200);
        }
        else{
            return response()->json(array('error'), 401);            
        }
    }
}
