<?php

use App\Button;
use App\HideForMe;
use App\Language;
use App\Menu;
use App\Multiplescreen;
use App\PackageMenu;
use Illuminate\Support\Facades\Auth;

//for get stripe plan
function getPlan()
{

    $userplan = auth()->user()->subscriptions()->orderBy('id', 'DESC')->first();

    $subscription = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    $subscription = \Stripe\Subscription::retrieve($userplan->stripe_id);

    if (isset($subscription) && $subscription->canceled_at == '' && $subscription->pause_collection == '') {

        return 1;

    } else {
        return 0;
    }
}

//for selected language
if (!function_exists('selected_lang')) {
    function selected_lang()
    {
        $lang = Language::firstWhere('local', '=', session()->get('changed_language') ?? config('translatable.fallback_locale'));
        return $lang;
    }
}
// for release update
if (!function_exists('get_release')) {

    function get_release()
    {

        $version = @file_get_contents(storage_path() . '/app/bugfixer/version.json');
        $version = json_decode($version, true);
        $current_subversion = isset($version['subversion']);

        return '(Release ' . $current_subversion . ')';
    }

}

function getSubscription()
{

    $subscribed = 0;
    $config = \App\Config::first();
    $auth = auth()->user();
    $nav_menus = Menu::query();
    $package_menu = PackageMenu::query();
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    if (isset($auth)) {

        $current_date = Illuminate\Support\Carbon::now();
        $paypal = App\PaypalSubscription::where('user_id', $auth->id)->orderBy('created_at', 'desc')->first();
        if (isset($paypal)) {

            if (date($current_date) <= date($paypal->subscription_to)) {

                if ($paypal->package_id == 0) {
                    $nav_menus = $nav_menus->get();

                    return response()->json([
                        'subs_type' => 'all_menu',
                        'nav_menus' => $nav_menus,
                        'subscribed' => true,
                        'status' => 'OK',
                    ]);

                }
            }
        }
        if ($auth->is_admin == 1 || $auth->is_assistant == 1) {

            $nav_menus = $nav_menus->orderBy('position', 'ASC')->get();
            return response()->json([
                'subs_type' => 'all_menu',
                'nav_menus' => $nav_menus,
                'subscribed' => true,
                'status' => 'OK',
            ]);

        } else {

            /** Stripe Subscription start */

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            if ($auth->stripe_id != null) {
                $customer = \Laravel\Cashier\Cashier::findBillable($auth->stripe_id);
              
                if (isset($customer)) {
                    $data = $auth->subscriptions->last();
                }
            }
            if (isset($paypal) && $paypal != null && $paypal->count() > 0) {
                $last = $paypal;
            }
            $stripedate = isset($data) ? $data->created_at : null;
            $paydate = isset($last) ? $last->created_at : null;
            if ($stripedate > $paydate) {

                if ($auth->subscribed($data->name) && date($current_date) <= date($data->subscription_to) && getPlan() == 1) {
                    if (isset($data->stripe_plan) && $data->stripe_plan != null) {
                        $planmenus = $package_menu->where('package_id', $data->stripe_plan)->get();

                        if (count($planmenus)) {
                            /** @return specfic plan menus */

                            $nav_menus = $nav_menus->whereIn('id', $planmenus->pluck('menu_id'))->get();
                            return response()->json([
                                'subs_type' => 'single_menu',
                                'nav_menus' => $nav_menus,
                                'subscribed' => true,
                                'status' => 'OK',
                            ]);

                        } else {
                            /** If pkg has no menu selected @return all menu */

                            $nav_menus = $nav_menus->orderBy('position', 'ASC')->get();
                            return response()->json([
                                'subs_type' => 'all_menu',
                                'nav_menus' => $nav_menus,
                                'subscribed' => true,
                                'status' => 'OK',
                            ]);
                        }

                    }
                } else {

                    return response()->json([
                        'subs_type' => 'all_menu',
                        'nav_menus' => $nav_menus,
                        'subscribed' => false,
                        'status' => 'FAIL',
                    ]);
                }
            } elseif ($stripedate < $paydate) {

                if ((date($current_date) <= date($last->subscription_to)) && $last->status == 1) {

                    if (isset($last->plan['plan_id']) && $last->plan['plan_id'] != null) {

                        $planmenus = $package_menu->where('package_id', $last->plan['plan_id'])->get();

                        if (count($planmenus)) {

                            /** @return specfic plan menus */

                            $nav_menus = $nav_menus->whereIn('id', $planmenus->pluck('menu_id'))->get();

                            return response()->json([
                                'subs_type' => 'single_menu',
                                'nav_menus' => $nav_menus,
                                'subscribed' => true,
                                'status' => 'OK',
                            ]);

                        } else {
                            /** If pkg has no menu selected @return all menu */
                            $nav_menus = $nav_menus->orderBy('position', 'ASC')->get();
                            return response()->json([
                                'subs_type' => 'all_menu',
                                'nav_menus' => $nav_menus,
                                'subscribed' => true,
                                'status' => 'OK',
                            ]);
                        }

                    }
                    else{
                        if($config->catlog == 0){
                            return response()->json([
                               
                                'subscribed' => false,
                                'status' => 'FAIL',
                            ]);
                        }else{
                            return response()->json([
                                'subs_type' => 'all_menu',
                                'nav_menus' => $nav_menus,
                                'subscribed' => false,
                                'status' => 'FAIL',
                            ]);
                        }
                    }

                } else {

                    return response()->json([
                        'subs_type' => 'all_menu',
                        'nav_menus' => $nav_menus,
                        'subscribed' => false,
                        'status' => 'FAIL',
                    ]);
                }
            } else {

                return response()->json([
                    'subs_type' => 'all_menu',
                    'nav_menus' => $nav_menus,
                    'subscribed' => false,
                    'status' => 'FAIL',
                ]);
            }
        }
    } else {
        return response()->json([
            'subscribed' => false,
            'status' => 'FAIL',
        ]);
    }
}

function checkInMovie($movie)
{

    if (getSubscription()->getData()->subscribed == true) {

        if (isset(getSubscription()->getData()->subs_type) && getSubscription()->getData()->subs_type == 'all_menu') {
            return true;
        }

        if (isset(getSubscription()->getData()->subs_type) && getSubscription()->getData()->subs_type == 'single_menu') {

            foreach ($movie->menus as $moviemenu) {
                if (array_search($moviemenu->menu_id, array_column(getSubscription()->getData()->nav_menus, 'id')) !== false) {
                    return true;
                }
            }
        }

    }
}

function checkInTvseries($tv)
{
    if (getSubscription()->getData()->subscribed == true) {

        if (isset(getSubscription()->getData()->subs_type) && getSubscription()->getData()->subs_type == 'all_menu') {
            return true;
        }

        if (isset(getSubscription()->getData()->subs_type) && getSubscription()->getData()->subs_type == 'single_menu') {

            foreach ($tv->menus as $tvmenu) {
                if (array_search($tvmenu->menu_id, array_column(getSubscription()->getData()->nav_menus, 'id')) !== false) {
                    return true;
                }
            }
        }

    }
}

function checkInViewAllTv($tv)
{
    if (getSubscription()->getData()->subscribed == true) {

        if (isset(getSubscription()->getData()->subs_type) && getSubscription()->getData()->subs_type == 'all_menu') {
            return true;
        }

        if (isset(getSubscription()->getData()->subs_type) && getSubscription()->getData()->subs_type == 'single_menu') {

            foreach ($tv['menus'] as $tvmenu) {
                if (array_search($tvmenu['menu_id'], array_column(getSubscription()->getData()->nav_menus, 'id')) !== false) {
                    return true;
                }
            }
        }

    }
}

function checkInViewAllMovie($tv)
{
    if (getSubscription()->getData()->subscribed == true) {

        if (isset(getSubscription()->getData()->subs_type) && getSubscription()->getData()->subs_type == 'all_menu') {
            return true;
        }

        if (isset(getSubscription()->getData()->subs_type) && getSubscription()->getData()->subs_type == 'single_menu') {

            foreach ($tv['menus'] as $tvmenu) {
                if (array_search($tvmenu['menu_id'], array_column(getSubscription()->getData()->nav_menus, 'id')) !== false) {
                    return true;
                }
            }
        }

    }
}

function timecalcuate($user_id, $movie_id, $type)
{

    if ($type == 'M') {
        $filename = 'time.json';
        if (file_exists(storage_path() . '/app/time/movie/user_' . $user_id . '/movie_' . $movie_id . '/' . $filename)) {
            $result = @file_get_contents(storage_path() . '/app/time/movie/user_' . $user_id . '/movie_' . $movie_id . '/' . $filename);
            $result = json_decode($result);
            $current_duration = isset($result->curDurration) && $result->curDurration != null ? $result->curDurration : 0;
            $total_duration = isset($result->totalDuration) && $result->totalDuration != null ? $result->totalDuration : 0;
            if ($current_duration == 0 && $total_duration == 0) {
                return $percentage = 0;
            } else {
                if ($total_duration != 0 && $total_duration != null) {
                    return $percentage = ($current_duration / $total_duration) * 100;
                } else {
                    return $percentage = 0;
                }

            }

        } else {
            return $percentage = 0;
        }
    }elseif($type == 'S'){
        $filename = 'time.json';
        if (file_exists(storage_path() . '/app/time/tv/user_' . $user_id . '/episode_' . $movie_id . '/' . $filename)) {
            $result = @file_get_contents(storage_path() . '/app/time/tv/user_' . $user_id . '/episode_' . $movie_id . '/' . $filename);
            $result = json_decode($result);
            $current_duration = isset($result->curDurration) && $result->curDurration != null ? $result->curDurration : 0;
            $total_duration = isset($result->totalDuration) && $result->totalDuration != null ? $result->totalDuration : 0;
            if ($current_duration == 0 && $total_duration == 0) {
                return $percentage = 0;
            } else {
                if ($total_duration != 0 && $total_duration != null) {
                    return $percentage = ($current_duration / $total_duration) * 100;
                } else {
                    return $percentage = 0;
                }

            }

        } else {
            return $percentage = 0;
        }
    }
}



function getprofile(){
    $config_multiplescreen = Button::first()->multiplescreen;
    if($config_multiplescreen == 1){
        $manageProfile = Multiplescreen::where('user_id',auth()->user()->id)->first();
        if(isset($manageProfile)){
            if($manageProfile->activescreen != NULL){
                if($manageProfile->screen1 == $manageProfile->activescreen){
                    return 'S1';
                }else if($manageProfile->screen2 == $manageProfile->activescreen){
                    return 'S2';
                }else if($manageProfile->screen3 == $manageProfile->activescreen){
                    return 'S3';
                }else{
                    return 'S4';
                }
            }else{
                return 'S1';
            }
        }
   
    }else{
        return 'S1';
    }
    
    
}

function hidedata($id,$type){
   if(Auth::check() && isset(Auth::user()->id)){
    if($type == 'M'){
        $hide_data = HideForMe::where('type',$type)->where('movie_id',$id)->where('user_id',auth()->user()->id)->whereJsonContains('profile',getprofile())->first();
       
        if(isset($hide_data) && $hide_data != NULL){
            return 1;
        }else{
            return 0;
        }
      }else{
        $hide_data = HideForMe::where('type',$type)->where('season_id',$id)->where('user_id',auth()->user()->id)->whereJsonContains('profile',getprofile())->first();
        if(isset($hide_data) && $hide_data != NULL){
            return 1;
        }else{
            return 0;
        }
      }
   } 
 
   
}
