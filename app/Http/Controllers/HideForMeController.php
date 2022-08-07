<?php

namespace App\Http\Controllers;

use App\Config;
use App\HideForMe;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HideForMeController extends Controller
{
 
    public function store(Request $request){
     
      $userid= auth()->user()->id;
      $profile =getprofile(); 
      $dataProfile [] =  $profile;

      $exists = HideForMe::where('user_id',$userid)->where('type',$request->type)->where('movie_id',$request->id)->orwhere('season_id',$request->id)->first();
      
      if(isset($exists)){
       
        if(is_array($exists->profile)){
         
          $marks = $exists->profile;
          if(in_array($profile,$marks)){
            if(count($marks) > 1){
            
              $array_search_value = array_search($profile,$marks);
              unset($marks[$array_search_value]);
  
              $marks = array_values($marks);
              $exists->profile = $marks;
              $exists->save();
            }else{
            
              $exists->delete();
            }
           
          }else{

            $exists->profile = array_merge($dataProfile,$marks);
            $exists->save();
          }
          
         
          return response()->json(['msg' => 'Data updated hide successfully !']);
        }else{
          
          $exists->profile = $dataProfile;
          $exists->save();
          return response()->json(['msg' => 'Data hide successfully !']);
        }
      
       
      }
      else{
        if($request->type == 'M'){
          $movieid = $request->id;
          $season_id = NULL;
        }else{
          $movieid = NULL;
          $season_id =$request->id;
        }
      
        $data = HideForMe::create([
              'user_id' => $userid,
              'type' => $request->type,
              'movie_id'=>$movieid,
              'season_id' => $season_id,
              'profile' => $dataProfile,
              'created_at' => date('Y-m-d h:i:s'),
              'updated_at' => date('Y-m-d h:i:s'),
          
            ]);

        if(isset($data)){
          return response()->json(['msg' => 'Data hide successfully !']);
        }else{
         return response()->json(['msg' => 'error']);
        }
      }
    }

    public function show(){
      $hideData = HideForMe::where('user_id',auth()->user()->id)->whereJsonContains('profile',getprofile())->get();
      
      $age = 0;
      $config = Config::first();
      if ($config->age_restriction == 1) {
          if (Auth::user()) {
              $user_id = Auth::user()->id;
              $user = User::findOrfail($user_id);
              $age = $user->age;
          }

      } else {
          $age = 100;
      }
      return view('user.hidedata',compact('hideData','age'));
    }
 
}
