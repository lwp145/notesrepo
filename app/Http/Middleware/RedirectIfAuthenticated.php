<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'web')
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }
        // switch($guard){
        //     case 'web':
        //         if(Auth::guard($guard)->check()){
        //             return redirect('/home');
        //         }
        //         break;
        //     default :
        //         if(!Auth::guard($guard)->check()){
        //             return json_encode(array([
        //                 'status'=>'error',
        //                 'message'=>'Authentication failed!'
        //             ]));
        //         }
        //         break;
        // }
        return $next($request);
    }
}