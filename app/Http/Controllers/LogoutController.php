<?php

namespace App\Http\Controllers;

use App\Multiplescreen;
use App\PaypalSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class LogoutController extends Controller
{
  
    public function logout()
    {

        if (Auth::user()['is_admin'] == 1) {
            //In case user is admin
            Auth::logout();
            Session::flush();
            return redirect('/')->with('success', 'Logged out !');
        } elseif (isset(Auth::user()->subscriptions)) {

            if (isset($mlt_screen) && $mlt_screen == 1) {

                $activesubsription = PaypalSubscription::where('user_id', Auth::user()->id)->where('status', '=', 1)->orderBy('created_at', 'desc')->first();

                if (isset($activesubsription)) {

                    $getscreens = Multiplescreen::where('user_id', '=', Auth::user()->id)->first();

                    if (isset($getscreens)) {

                        $macaddress = $_SERVER['REMOTE_ADDR'];

                        if ($getscreens->device_mac_1 == $macaddress) {

                            $getscreens->device_mac_1 = null;
                            $getscreens->screen_1_used = 'NO';

                        } elseif ($getscreens->device_mac_2 == $macaddress) {

                            $getscreens->device_mac_2 = null;
                            $getscreens->screen_2_used = 'NO';

                        } elseif ($getscreens->device_mac_3 == $macaddress) {

                            $getscreens->device_mac_3 = null;
                            $getscreens->screen_3_used = 'NO';

                        } elseif ($getscreens->device_mac_4 == $macaddress) {

                            $getscreens->device_mac_4 = null;
                            $getscreens->screen_4_used = 'NO';

                        }

                        $getscreens->save();
                        Session::flush();
                        Auth::logout();
                        return redirect('/')->with('success', __('Logged out !'));

                    } else {
                        //In case screen not found
                        Auth::logout();
                        Session::flush();
                        return redirect('/')->with('success',__('Logged out !'));
                    }

                } else {
                    //In case user is not subscribed
                    Auth::logout();
                    Session::flush();
                    return redirect('/')->with('success', __('Logged out !'));
                }
            } else {
                //In case user is not subscribed
                Auth::logout();
                Session::flush();
                return redirect('/')->with('success', __('Logged out !'));
            }

        } else {
            //In case user is not subscribed
            Auth::logout();
            Session::flush();
            return redirect('/')->with('success', __('Logged out Successfully !'));
        }

    }
}
