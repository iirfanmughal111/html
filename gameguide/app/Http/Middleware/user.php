<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;


class user
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
            // The user is logged in...
            if(Auth::user()->role_id == 2 || Auth::user()->role_id == 3){
                return $next($request);
             }else{
                //admin user login, then redirect to dashboard
                return redirect('/admin/dashboard');
             }
        }
        return $next($request);
    }
}