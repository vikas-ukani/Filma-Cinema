<?php

namespace App\Http\Middleware;

use Closure;

class IsInstalled
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
        $isInstall = env('IS_INSTALLED');

        if ($isInstall == 1) {
            return $next($request);
        } else {
            return redirect()->route('eulaterm');
        }

    }
}
