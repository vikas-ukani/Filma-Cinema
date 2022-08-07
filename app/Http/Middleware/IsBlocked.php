<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsBlocked
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
        if (Auth::check()) {
            $auth = Auth::user();
            if ($auth->is_blocked == 0) {
                return $next($request);
            } else {
                if ($request->is('api/*')) {
                    return response()->json('Blocked User', 401);
                } else {
                    Auth::logout();
                    return redirect('/')->with('deleted',__('You Do Not Have Access to This Site Anymore. You Were Blocked.'));
                }
            }
        }
    }

}
