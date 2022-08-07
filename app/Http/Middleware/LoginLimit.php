<?php

namespace App\Http\Middleware;

use App\Button;
use App\Config;
use App\Multiplescreen;
use App\PaypalSubscription;
use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $mlti_screen = Button::first()->multiplescreen;
        if (isset($mlti_screen) && $mlti_screen == 1) {

            $auth = Auth::user();

            $getscreen = Multiplescreen::where('user_id', Auth::user()->id)->first();

            if ($auth->stripe_id != null) {
                if (Auth::user()->subscriptions) {
                    $data = $auth->subscriptions->last();
                    if(isset($data) && $data != NULL){
                        $stripedate = isset($data) ? $data->created_at : null;
                        $current_date = Carbon::now();
                        if ($auth->subscribed($data->name) && date($current_date) <= date($data->subscription_to) && $data->ends_at == null) {
                            Session::put('nickname', Auth::user()->name);
                            return $next($request);
                        }
                    }
                   
                }
            }

            $config = Config::first();
            if ($config->free_sub == 1) {
                $ps = PaypalSubscription::where('user_id', $auth->id)->first();

                if (isset($ps)) {
                    if ($ps->method == 'free') {
                        Session::put('nickname', Auth::user()->name);
                        return $next($request);
                    }
                }
            }

            if (!empty(Session::get('nickname'))) {

                return $next($request);

            } elseif ($auth->is_admin == 1) {

                return $next($request);

            } elseif (!isset($getscreen)) {
                return redirect()->route('manageprofile', $auth->id);
            } else {
                return redirect()->route('manageprofile', $auth->id);
            }

        } else {
            return $next($request);
        }
    }
}
