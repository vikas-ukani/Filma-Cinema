<?php

namespace App\Http\Controllers;

use App\Multiplescreen;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class MultipleScreenController extends Controller
{
   

    public function newupdate(Request $request, $id)
    {

        $macaddress = $_SERVER['REMOTE_ADDR'];

        $screenNo = $request->count;
        $screenrow = Multiplescreen::where('user_id', $id)->first();
        if ($screenNo == 1) {
            $query = Multiplescreen::where('user_id', $id)->update(['device_mac_1' => $macaddress, 'screen_1_used' => 'YES', 'activescreen' => $request->screen]);

            //check screen 2 is used by same user or not if yes than update null
            if ($screenrow->device_mac_2 != '' && $screenrow->device_mac_2 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_2' => null, 'screen_2_used' => 'NO']);
            }

            //check screen 3 is used by same user or not if yes than update null
            if ($screenrow->device_mac_3 != '' && $screenrow->device_mac_3 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_3' => null, 'screen_3_used' => 'NO']);
            }

            //check screen 4 is used by same user or not if yes than update null
            if ($screenrow->device_mac_4 != '' && $screenrow->device_mac_4 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_4' => null, 'screen_4_used' => 'NO']);
            }

        } elseif ($screenNo == 2) {

            $query = Multiplescreen::where('user_id', $id)->update(['device_mac_2' => $macaddress, 'screen_2_used' => 'YES', 'activescreen' => $request->screen]);

            //check screen 1 is used by same user or not if yes than update null
            if ($screenrow->device_mac_1 != '' && $screenrow->device_mac_1 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_1' => null, 'screen_1_used' => 'NO']);
            }

            //check screen 3 is used by same user or not if yes than update null
            if ($screenrow->device_mac_3 != '' && $screenrow->device_mac_3 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_3' => null, 'screen_3_used' => 'NO']);
            }

            //check screen 4 is used by same user or not if yes than update null
            if ($screenrow->device_mac_4 != '' && $screenrow->device_mac_4 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_4' => null, 'screen_4_used' => 'NO']);
            }

        } elseif ($screenNo == 3) {

            Multiplescreen::where('user_id', $id)->update(['device_mac_3' => $macaddress, 'screen_3_used' => 'YES', 'activescreen' => $request->screen]);

            //check screen 1 is used by same user or not if yes than update null
            if ($screenrow->device_mac_1 != '' && $screenrow->device_mac_1 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_1' => null, 'screen_1_used' => 'NO']);
            }

            //check screen 2 is used by same user or not if yes than update null
            if ($screenrow->device_mac_2 != '' && $screenrow->device_mac_2 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_2' => null, 'screen_2_used' => 'NO']);
            }

            //check screen 4 is used by same user or not if yes than update null
            if ($screenrow->device_mac_4 != '' && $screenrow->device_mac_4 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_4' => null, 'screen_4_used' => 'NO']);
            }

        } elseif ($screenNo == 4) {
            $query = Multiplescreen::where('user_id', $id)->update(['device_mac_4' => $macaddress, 'screen_4_used' => 'YES', 'activescreen' => $request->screen]);

            //check screen 1 is used by same user or not if yes than update null
            if ($screenrow->device_mac_1 != '' && $screenrow->device_mac_1 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_1' => null, 'screen_1_used' => 'NO']);
            }

            //check screen 2 is used by same user or not if yes than update null
            if ($screenrow->device_mac_2 != '' && $screenrow->device_mac_2 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_2' => null, 'screen_2_used' => 'NO']);
            }

            //check screen 3 is used by same user or not if yes than update null
            if ($screenrow->device_mac_3 != '' && $screenrow->device_mac_3 == $macaddress) {
                Multiplescreen::where('user_id', $id)->update(['device_mac_3' => null, 'screen_3_used' => 'NO']);
            }
        }

        Session::forget('nickname');
        Session::put('nickname', $request->screen);

        if ($query) {
            return "$request->screen is now Active Profile !";
        } else {
            return "Something Went Wrong ! Please Try again !";
        }
    }

    public function manageprofile($id)
    {

        $result = Multiplescreen::where('user_id', $id)->first();

        if (!isset($result)) {
            Session::forget('nickname');

            $value = Auth::user()->paypal_subscriptions->last();

            if ($value != null) {
                if ($value['status'] == 1) {

                    $muser = new Multiplescreen;

                    $getpkgid = $value->package_id;

                    $pkg = Package::where('id', $value->package_id)->first();

                    if (isset($pkg)) {
                        $screen = $pkg->screens;
                        $muser->pkg_id = $pkg->id;
                        $muser->user_id = Auth::user()->id;

                        if ($screen == 1) {
                            $muser->screen1 = Auth::user()->name;

                        } elseif ($screen == 2) {
                            $muser->screen1 = Auth::user()->name;
                            $muser->screen2 = "Screen2";
                        } elseif ($screen == 3) {
                            $muser->screen1 = Auth::user()->name;
                            $muser->screen2 = "Screen2";
                            $muser->screen3 = "Screen3";
                        } elseif ($screen == 4) {
                            $muser->screen1 = Auth::user()->name;
                            $muser->screen2 = "Screen2";
                            $muser->screen3 = "Screen3";
                            $muser->screen4 = "Screen4";
                        }

                        $muser->save();

                    }
                }
            }

        }

        //Check if user changed the plan update screen accroding to it
        if (isset($result)) {
            $value = Auth::user()->paypal_subscriptions->last();
            if ($value != null) {
                if ($value['status'] == 1 && $value->package_id != $result->pkg_id) {
                    $result->delete();
                    $muser = new Multiplescreen;
                    $pkg = Package::find($value->package_id);

                    if (isset($pkg)) {

                        $screen = $pkg->screens;
                        $muser->pkg_id = $pkg->id;
                        $muser->user_id = Auth::user()->id;

                        if ($screen == 1) {
                            $muser->screen1 = Auth::user()->name;

                        } elseif ($screen == 2) {
                            $muser->screen1 = Auth::user()->name;
                            $muser->screen2 = "Screen2";
                        } elseif ($screen == 3) {
                            $muser->screen1 = Auth::user()->name;
                            $muser->screen2 = "Screen2";
                            $muser->screen3 = "Screen3";
                        } elseif ($screen == 4) {

                            $muser->screen1 = Auth::user()->name;
                            $muser->screen2 = "Screen2";
                            $muser->screen3 = "Screen3";
                            $muser->screen4 = "Screen4";
                        }

                        $muser->save();

                    }

                }
            }

        }

        $result = Multiplescreen::where('user_id', $id)->first();

        return view('manageprofile', compact('result'));

    }

    public function updateprofile(Request $request, $id)
    {

        $result = Multiplescreen::where('user_id', $id)->first();
        $macaddress = $_SERVER['REMOTE_ADDR'];

        if ($request->screen1 != null || $request->screen1 != '') {
            $result->screen1 = $request->screen1;

        }

        if ($request->screen2 != null || $request->screen2 != '') {
            $result->screen2 = $request->screen2;
        }

        if ($request->screen3 != null || $request->screen3 != '') {
            $result->screen3 = $request->screen3;
        }

        if ($request->screen4 != null || $request->screen4 != '') {
            $result->screen4 = $request->screen4;
        }

        if ($result->device_mac_1 != '' && $macaddress == $result->device_mac_1) {
            Session::put('nickname', $request->screen1);
        } elseif ($result->device_mac_2 != '' && $macaddress == $result->device_mac_2) {
            Session::put('nickname', $request->screen2);
        } elseif ($result->device_mac_3 != '' && $macaddress == $result->device_mac_3) {
            Session::put('nickname', $request->screen3);
        } elseif ($result->device_mac_4 != '' && $macaddress == $result->device_mac_4) {
            Session::put('nickname', $request->screen4);
        }

        $result->save();

        return back()->with('updated', __('Profile Updated Successfully !'));
    }
}
