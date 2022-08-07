<?php

namespace App\Http\Middleware;

use App\Config;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IsSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        App::setLocale(Session::get('changed_language'));
        $config = Config::first();
        if (Auth::check()) {
            $auth = auth()->user();
            if ($auth->is_admin == 1 || $auth->is_assistant == 1 || $config->free_sub == 1) {

                return $next($request);
            } else {
                if ($config->catlog == 0) {
                    if (getSubscription()->getData()->subscribed == true) {
                        return $next($request);
                    } else {

                        return redirect('account/purchaseplan')->with('deleted', __('You have no subscription please subscribe'));
                    }
                } else {
                    if ($config->withlogin == 1) {
                        if (getSubscription()->getData()->subscribed == true) {
                            return $next($request);
                        }else{
                            return redirect('account/purchaseplan')->with('deleted', __('You have no subscription please subscribe'));
                        }
                    } else {
                        if (getSubscription()->getData()->subscribed == true) {
                            return $next($request);
                        }else{
                            return redirect('account/purchaseplan')->with('deleted', __('You have no subscription please subscribe'));
                        }
                    }
                }
            }

        } else {
            if ($config->remove_landing_page == 1) {
                return view('auth.login');
            } else {
                return redirect('/');
            }
        }
    }
}
