<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\AccessCodes;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use App\Models\AccessLog;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class RegisterUserCode {

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
     
		 $clientAccesslog=AccessLog::where('access_code',$a_code)->first();
     
	
        //  dd($userAccessCode->user_id);
     
        if($userAccessCode && !$userAccessCode->user_id){

           
                $userCreateCode=$this->fakeUser();
            
                DB::table('add_access_codes')->where('serial_id', $userAccessCode->serial_id)
                ->update(['is_manual' => 0,'user_id' => $userCreateCode->id , 'used_date' =>  Carbon::now()->timestamp]);
                Auth::login($userCreateCode);
                return $next($request);

            }
		
		  if($clientAccesslog && !$clientAccesslog->redeemed_user_profile){
		  
			   $userCreateCode=$this->fakeUser();
            
                DB::table('client_acess_log')->where('client_acess_log_id', $clientAccesslog->client_acess_log_id)
                ->update(['generation_date' => Carbon::now()->timestamp,'redeemed_status'=>1,'redeemed_user_profile' =>         $userCreateCode->id]);
                
			  $this->CreateAccessCode($a_code,$userCreateCode->id);
			
			  Auth::login($userCreateCode);
              
			  return $next($request);
		  
		  }
        
      
        
            return $next($request);
        }


    public function fakeUser(){

        $fristName='Firstname_'.Str::random(5);
        $lastName='Lastname_'.Str::random(5);

    $user = User::create([
    
             'first_name' => $fristName,
             'last_name' => $lastName,
             'email' => $fristName.'@'.$lastName.'.com',
             'role_id' => 2,
             'status' => 1,
             'password' => Hash::make($fristName.$lastName),
             'plan_start_on' =>  Carbon::now()->format('Y-m-d'),
			 'plan_id'=>2

         ]);

         return $user;
    }
	
	public function CreateAccessCode($a_code,$userId){
	
		$AccessCode=AccessCodes::create([
		 
			 'number' => $a_code,
             'category_id' => null,
             'skey' => null,
             'user_id' => $userId,
             'is_manual' => 1,
             'used_date' => Carbon::now()->timestamp,
             'end_date' => 0,
			
		 ]);
	
	
	}


}
