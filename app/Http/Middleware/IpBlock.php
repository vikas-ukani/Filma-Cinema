<?php

namespace App\Http\Middleware;

use Closure;
use App\Button;

class IpBlock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
   /* public function handle($request, Closure $next)
    {

        if (isset($button) && $button->ip_block == '1') {
            $block_ip = $button->block_ips;

            if(in_array($request->ip(),$this->block_ip))
            {
                return response()->json(['message'=>"you don't valid ip address"]);
            }
             return $next($request);

        } else {
            return $next($request);
        }
    }*/
    public function handle($request, Closure $next)
    {
        $button = Button::first();
        if(isset($button) && $button->ip_block == '1'){
            $restrictIps = $button->block_ips;
            if(in_array($request->ip(),$restrictIps))
            {
                abort(403);
            }
            return $next($request);
        }else{
        return $next($request);
        }
    }
}


    

