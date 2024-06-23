<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;


class admin
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
            if(Auth::user()->role_id == 1){
                return $next($request);
             }else{
                //front user login, then redirect to home
                return redirect('/');
             }
        }
        return redirect('/admin/login');
        //return $next($request);
        /*if (Session::get('admin_user_id')) {

               return $next($request);
        }
		
		return redirect('/admin/login');*/
    }
}
