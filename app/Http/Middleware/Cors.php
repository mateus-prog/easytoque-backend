<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->header('Access-Control-Allow-Origin', "*");
        $response->header('Access-Control-Allow-Methods', "PUT, POST, DELETE, GET, OPTIONS");
        //$response->header('Access-Control-Allow-Headers', "Accept, Authorization, Content-Type");
        //$response->header('Access-Control-Allow-Headers', "Origin, Content-Type, Accept, Authorization, X-Request-With");
        //$response->header('Access-Control-Allow-Headers', "Origin, X-Requested-With, Content-Type, Accept, x-client-key, x-client-token, x-client-secret, Authorization");
        $response->header('Access-Control-Allow-Headers', '*');
        return $response;
    }
}
