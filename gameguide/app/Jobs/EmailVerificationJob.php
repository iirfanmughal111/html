<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EmailVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //d9592d3a34034f938c35ddcf06be6168
        $allUsers = User::where('email_status',NULL)->get();
        if(!empty($allUsers)){
            $api_key = 'ac87e4224d564b2b8d77f6594c2002eb';
            $request_endpoint = 'https://api.zerobounce.net/v2/validate?api_key='.$api_key.'&email=';

            foreach($allUsers as $key=>$user){
            
                 // https://www.zerobounce.net/docs/zerobounce-api-wrappers/#api_wrappers__v2__php
                $userEmail = $user->email;
                  
                 // use curl to make the request
                $url = $request_endpoint.urlencode($userEmail);

                 $ch = curl_init($url);
                //PHP 5.5.19 and higher has support for TLS 1.2
                curl_setopt($ch, CURLOPT_SSLVERSION, 6);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); 
                curl_setopt($ch, CURLOPT_TIMEOUT, 150); 
                $response = curl_exec($ch);
                curl_close($ch);

                //decode the json response
                 $json = json_decode($response, true);
                if ($json['status']=='valid'){
                    User::where('id', $user->id)->update(['email_status' => 1]);

                    }
                    else{
                                User::where('id', $user->id)->update(['email_status' => 0]);
                    }
                            
                           
            }
        }
        
    }
}
