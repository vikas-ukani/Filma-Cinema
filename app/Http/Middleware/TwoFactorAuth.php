<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TwoFactorAuth
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
        if (auth()->check()) {

            if (auth()->user()->google2fa_enable == '1') {
               
                if (Cookie::get('two_fa') == 1) {
                    return $next($request);
                } else {

                    return Response(view('auth.otp'));
                }

            } else {
                return $next($request);
            }

        } else {
            return $next($request);
        }

    }
}
