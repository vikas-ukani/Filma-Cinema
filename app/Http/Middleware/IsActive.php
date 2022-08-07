<?php

namespace App\Http\Middleware;

use Closure;

class IsActive
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
        $isactive = @file_get_contents(public_path() . '/config.txt');
        if ($isactive) {
            if ($isactive == 1) {

                $response = $next($request);

                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');

                return $response;

            } else {
                return Response(view('accessdenied'));

            }
        } else {
            return Response(view('accessdenied'));
        }
    }
}
