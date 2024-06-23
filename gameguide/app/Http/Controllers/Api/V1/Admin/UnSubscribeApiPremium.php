<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessCodes;
use App\Models\AccessLog;
use App\Models\User;
use DB;

class UnSubscribeApiPremium extends Controller {

    public function getData(Request $request) {

        $code = $request->input('access_code');

      
        if (AccessCodes::where('number', $code)->exists()) {


            $userAccessCode = DB::table('add_access_codes')->where('number', $code)->first();

            $AccessLog = DB::table('client_acess_log')->where('access_code', $code)->first();



            
            $User = User::find($userAccessCode->user_id);

           
            if ($User) {

                DB::table('add_access_codes')->where('serial_id', $userAccessCode->serial_id)
                ->update(['is_manual' => 0]);

                if( $AccessLog){

                        DB::table('client_acess_log')->where('client_acess_log_id', $AccessLog->client_acess_log_id)
                        ->update(['redeemed_user_profile' => null,'redeemed_status' => 0]);
                    
               }
                $User->status = null;
                $User->save();
            }

           return response()->json('success', 200);
           
//                       
        } else {

            return response()->json(['error' => 'Invalid Code'], 401);
        }

    }

}
