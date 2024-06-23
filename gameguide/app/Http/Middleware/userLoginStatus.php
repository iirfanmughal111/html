<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;

class userLoginStatus {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        if (Auth::check()) {


            if (Auth::user()->status == 0 || Auth::user()->status == null) {
                Auth::logout();
                return redirect('/login')->with('error', 'Your account is deactivated.');
            }
        }
        return $next($request);
    }

}
