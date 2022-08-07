<?php

namespace App\Http\Middleware;

use App\Language;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SwitchLanguage
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

        $def_lang = Language::where('def', '=', 1)->first();

        if (!Session::has('changed_language')) {

            if (isset($def_lang)) {

                Session::put('changed_language', $def_lang->local);

            } else {
                Session::put('changed_language', 'en');
            }

        }

        App::setLocale(Session::get('changed_language'));

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}
