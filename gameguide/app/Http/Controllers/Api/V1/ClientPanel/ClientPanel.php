<?php

namespace App\Http\Controllers\Api\V1\ClientPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessCodes;
use App\Models\User;
use DB;
use Carbon\Carbon;
class ClientPanel extends Controller {

    public function totalAccessCode(Request $request) {

	
        $timestamp=Carbon::now()->timestamp;

        $startOfMonth=strtotime(date("Y-m-1 00:00:00"));
         
        $beginOfDay = strtotime("today", $timestamp);
        $endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;


        $totalTodayCodes=DB::table('add_access_codes')->whereBetween('used_date', [$beginOfDay, $endOfDay])->get();
        $totalMonthCodes=DB::table('add_access_codes')->whereBetween('used_date', [$startOfMonth, $timestamp])->get();
        $totalCodes=DB::table('add_access_codes')->where('used_date','!=','0')->where('end_date','=','0')->get();

        return response()->json(['totalTodayCodes' => $totalTodayCodes->count(), 'totalMonthCodes' => $totalMonthCodes->count(), 'totalCodes' => $totalCodes->count()]);
      
		
        $code = $request->input('access_code');


	}
	
	
    public function graph(){


     
        $records = DB::select("SELECT DATE_FORMAT(FROM_UNIXTIME(used_date), '%Y-%c-%d') as timeperiod,Count(*) as total
        FROM `add_access_codes`
        WHERE user_id is not null AND end_date = 0 AND used_date!=0
        GROUP BY DATE_FORMAT(FROM_UNIXTIME(used_date), '%Y-%c-%d') ORDER BY used_date ASC");

             

        return response()->json(['records' => $records]);

	}
	
	  public function latestCode(){

    
        $latestCode=DB::select("SELECT number,users.first_name as username,used_date FROM add_access_codes JOIN users ON add_access_codes.user_id=users.id ORDER BY used_date DESC LIMIT 10");
        
        return response()->json(['latestCode' => $latestCode]);
        
    }
	
	   public function accessCode(Request $request){

		   $start = $request->input('start');
		   $limit = $request->input('limit');
		   
		    $code_like = $request->input('code_like');
		  
		 
		   
		  $totalAccessCodes=DB::table('add_access_codes')->where('user_id','!=','0')->get();

		          $latestCode=DB::select("SELECT serial_id,number,users.first_name as username,used_date FROM add_access_codes JOIN users ON add_access_codes.user_id=users.id where add_access_codes.number LIKE '%$code_like%' ORDER BY used_date DESC LIMIT $start,$limit");
		   
		   //  var_dump($latestCode);exit;
       // $latestCode=DB::select("SELECT number,users.first_name as username,used_date FROM add_access_codes JOIN users ON add_access_codes.user_id=users.id ORDER BY used_date DESC LIMIT $start,$limit");
        
        return response()->json(['accessCodes' => $latestCode,'totalAccessCodes' => $totalAccessCodes->count()]);

    }
	
	public function editAccessCode(Request $request){
		
		 	$serial_id = $request->input('serial_id');
		
	     $number = $request->input('number');
		
		 $code = AccessCodes::where('serial_id', '=',  $serial_id)->update(['number'=>$number]);
      	return response()->json(true);
		
	}
	
	public function deleteAccessCode(Request $request){
	
		$serial_id = $request->input('serial_id');
		DB::delete('delete from add_access_codes where serial_id  = ?',[$serial_id]);
	}
	
		public function exportCodes(Request $request){
		
		$startdate = $request->input('startdate');
		$enddate = $request->input('enddate');
		
		if($startdate && $enddate){
		
			 $Codes=DB::select("SELECT number as access_code, used_date as date FROM add_access_codes JOIN users ON add_access_codes.user_id=users.id Where used_date > $startdate and used_date < $enddate ORDER BY used_date DESC");
			
			
		}elseif($startdate){
		
			 $Codes=DB::select("SELECT number as access_code, used_date as date FROM add_access_codes JOIN users ON add_access_codes.user_id=users.id Where used_date > $startdate ORDER BY used_date DESC");
			
		}elseif($enddate){
		
			 $Codes=DB::select("SELECT number as access_code, used_date as date FROM add_access_codes JOIN users ON add_access_codes.user_id=users.id Where used_date < $enddate ORDER BY used_date DESC");
			
		}else{
			
			  $Codes=DB::select("SELECT number as access_code, used_date as date FROM add_access_codes JOIN users ON add_access_codes.user_id=users.id ORDER BY used_date DESC");
		}
		
		return response()->json($Codes);
	
	}
	

	public function exportlog(Request $request){
		
		$startdate = $request->input('startdate');
		$enddate = $request->input('enddate');
		$status = $request->input('status');
		
		
          
        if ($status && ($startdate && $enddate)) {

             $data = DB::select("SELECT access_code, generation_date FROM `client_acess_log` WHERE generation_date > $startdate and generation_date < $enddate and redeemed_status=$status");
      
                
        } elseif ($status && $startdate) {

                 $data = DB::select("SELECT access_code, generation_date as date FROM `client_acess_log` WHERE  generation_date > $startdate  and redeemed_status=$status");
                 
        } elseif ($status and $enddate) {

                 $data = DB::select("SELECT access_code, generation_date as date FROM `client_acess_log` WHERE generation_date < $enddate and redeemed_status=$status");
       
        }elseif ($status) {
            
          $data = DB::select("SELECT access_code, generation_date as date FROM `client_acess_log` WHERE redeemed_status=$status");
        } 
        elseif ($startdate and $enddate ) {

             $data = DB::select("SELECT access_code, generation_date as date FROM `client_acess_log` WHERE generation_date > $startdate and generation_date < $enddate");
     
        } 
         elseif ($startdate) {
            
             $data = DB::select("SELECT access_code, generation_date as date FROM `client_acess_log` WHERE d generation_date > $startdate");
        }
         elseif ( $enddate ) {

             $data = DB::select("SELECT access_code, generation_date as date FROM `client_acess_log` WHERE generation_date < $enddate");   
        }
        else {

            $data = DB::select("SELECT access_code, generation_date as date FROM `client_acess_log`");
        }
		
	
		
		return response()->json($data);
	
	}
	
	public function allAccessCode(){
	
		 $allAccessCode=DB::select('SELECT number as access_code,used_date as date from add_access_codes where used_date!=0 and end_date=0');
		
		return response()->json($allAccessCode);
	
	}
	
	public function MontlyAccessCode(){
	
		 $timestamp=Carbon::now()->timestamp;

        $startOfMonth=strtotime(date("Y-m-1 00:00:00"));
		
			 $MontlyAccessCode=DB::select("SELECT number as access_code,used_date as date from add_access_codes WHERE used_date BETWEEN '".$startOfMonth."' AND '".$timestamp."' and user_id is not null and end_date = 0");
		
		return response()->json($MontlyAccessCode);
		
	}
	
		public function TodayAccessCode(){
	
			
		  $timestamp=Carbon::now()->timestamp;
         
        $beginOfDay = strtotime("today", $timestamp);
        $endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;;
		
			 $TodayAccessCode=DB::select("SELECT number as access_code,used_date as date from add_access_codes WHERE used_date BETWEEN  '".$beginOfDay."' AND '".$endOfDay."' and user_id is not null and end_date = 0");
		
		return response()->json($TodayAccessCode);
		
	}
	
	
	   public function accessLog(Request $request){

		   $start = $request->input('start');
		   $limit = $request->input('limit');
		   
		  $code_like = $request->input('code_like');
		  
		 
		   
		  $totalAccessLog=DB::table('client_acess_log')->get();

		          $accesslog=DB::select("SELECT access_code,generation_client,generation_date,redeemed_status,redeemed_user_profile FROM client_acess_log where access_code LIKE '%$code_like%' ORDER BY generation_date DESC LIMIT $start,$limit");
		   

        return response()->json(['accesslog' => $accesslog,'totalAccessLog' => $totalAccessLog->count()]);

    }
	
}
