<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\AccessCodes;
use App\Models\User;
use Illuminate\Support\Facades\URL;
class AutoLogin {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $a_code=$request->input('a');
        
        $userAccessCode=AccessCodes::where('number',$a_code)->first();
     
        if($userAccessCode){

            $userId=$userAccessCode->user_id;
            $user = User::find($userId);

            if($user){

                Auth::login($user);
                return $next($request);
            }
        
            return $next($request);
        

        }


        return $next($request);
      
    }

}
