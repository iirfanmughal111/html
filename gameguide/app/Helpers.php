<?php
//use DB;

//namespace App\Http\Middleware;
use App\Models\Role;
use App\Models\User;
use App\Models\RolesPermission;
use App\Models\PermissionList;
use App\Models\Setting;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Notification;
use App\Models\EmailTemplate;
use App\Models\StateList;
use App\Models\CityLists;
use App\Models\Company;
use App\Models\Plan;
use Carbon\Carbon;
use App\Models\MessageStatus;
use App\Models\WebinarRegistraion;


function random_light_color() {
    return sprintf('%06X', mt_rand(0, 0xFFFFFF));
}
function random_dark_color() {
    $dt = '';
    for($o=1;$o<=3;$o++)
    {
        $dt .= str_pad( dechex( mt_rand( 0, 127 ) ), 2, '0', STR_PAD_LEFT);
    }
    return $dt;
}
function banner_photo($user_id){
	
	      $user_data = User::where('id',$user_id)->get();
		  $banner_image =   url('/uploads/users').'/'. $user_data[0]->id .'/'. $user_data[0]->banner_photo;
		  return $banner_image ;

}

//use Config;
// Return User Role ID 
function current_user_role_id(){
	$user = \Auth::user();
	return $user->role_id;
}

function current_user_role_name(){
	$user = \Auth::user();
	$role = Role::where('id',$user->role_id)->get();
	return $role[0]->slug;
} 
/* Get Loggedin User data */
function user_data(){
	$user = \Auth::user();
	return $user;
}

/* Get Current User ID */
function user_id(){
	$user = \Auth::user();
	return $user->id;
}

/* Get User data by ID  */
function user_data_by_id($id){
	$userData = User::where('id',$id)->get();
	return $userData[0];
}

/* Explode by  */
function explodeTo($sign,$data){
	$exp = explode($sign,$data);
	return $exp;
}


function role_data_by_id($id){
	$role = Role::where('id',$id)->get();
	return $role[0];
} 



/* Exploade by |  */ 
function split_to_array($sign,$data){
		$data = explode($sign,$data);
		return $data;
}

/* ================================
   If double authentication not set then redirect to below routes of user role base 
============================*/
function redirect_route_name(){
	
	  $role_id = Config::get('constant.role_id');
	  $user_id =user_id();
	  $user_data = user_data_by_id($user_id);
			
	  if(is_null($user_data->otp)){
		  
	   // IF DATA_ADMIN/DATA_ANALYST/CUSTOMER_USER/CUSTOMER_ADMIN 
	   
	   if($role_id['SUPER_ADMIN']== current_user_role_id()){
		  /*return 'admin/dashboard'; */
		  return 'admin/account'; 
	   }
	   else if($role_id['NORMAL_USER']== current_user_role_id()){
		 	return 'user-profile';				
	//    } else if($role_id['INDIA_HEAD']== current_user_role_id()){
	// 	 	return 'user-profile';
			} else if($role_id['STATE_HEAD']== current_user_role_id()){
	 	 	return 'user-profile';					
	   }else if(current_user_role_id() > 2){
		  return 'user-profile';	
	   }
	   	  
	   }else{
		    \Auth::logout();
		   return 'login'; 
	  }  
}

function profile_photo($user_id){
	   
	  $user_data = User::where('id',$user_id)->get();

	  $profile_photo =  url('/uploads/users').'/'. $user_data[0]->id .'/'. $user_data[0]->profile_photo;
	  return $profile_photo ;

}

function check_role_access($permission_slug){
	
	$user = \Auth::user();
	$current_user_role_id = $user->role_id;
	
	$permission_list_for_role = RolesPermission::where('role_id',$current_user_role_id)->get();
	
	
	$permission_array = array();
	foreach($permission_list_for_role as $permission){
			
		 $slug = PermissionList::where('id',$permission->permission_id)->select('slug')->first();
		 $permission_array[] = $slug->slug;
	}
	
	if(in_array($permission_slug,$permission_array)){
		return true;
	}else{
		return false;
	}
}

function access_denied_user($permission_slug,$already_check = 0){
	$user = \Auth::user();
	$current_user_role_id = $user->role_id;
	
	$permission_list_for_role = RolesPermission::where('role_id',$current_user_role_id)->get();
//	pr($permission_list_for_role->toArray());
	
	$permission_array = array();
	foreach($permission_list_for_role as $permission){
			
		 $slug = PermissionList::where('id',$permission->permission_id)->select('slug')->first();
		 $permission_array[] = $slug->slug;
	}
	
	if(in_array($permission_slug,$permission_array)){
		return true;
	}else{
		/*check if admin user login*/
		//check session admin id
		if(!empty(Session::get('is_admin_login'))  && Session::get('is_admin_login') == 1 && !empty(Session::get('admin_user_id')) && $already_check == 0){
			Auth::loginUsingId(Session::get('admin_user_id'));
			access_denied_user($permission_slug,1);
		}else{
			return abort_unless(\Gate::denies(current_user_role_name()), 403);
		}
	}
}

/* // USER/ANALYST NOT ALBE TO ACCESS 
function access_denied_user(){
	
		$role_id = Config::get('constant.role_id');
	    if($role_id['CUSTOMER_USER']== current_user_role_id()){
		  return abort_unless(\Gate::denies(current_user_role_name()), 403);
	    } 
} */

function access_denied_user_analyst(){
	
		$role_id = Config::get('constant.role_id');
	    if($role_id['CUSTOMER_USER']== current_user_role_id() || $role_id['DATA_ANALYST']== current_user_role_id()){
		  return abort_unless(\Gate::denies(current_user_role_name()), 403);
	    } 
	
}


//EMAIL SEND 
 function send_email($to='',$subject='',$message='',$from='',$fromname=''){
	try {	
			$mail = new PHPMailer();
			$mail->isSMTP(); // tell to use smtp
			$mail->CharSet = "utf-8"; // set charset to utf8
			
			$setting = Setting::where('id',1)->get();
	
			$mail->SMTPAuth = true;
			$mail->Host = $setting[0]->smtp_host;
			$mail->Port = $setting[0]->smtp_port;
			$mail->Username =$setting[0]->smtp_user;
            $mail->Password = urlsafe_b64decode($setting[0]->smtp_password); 		
			/* $mail->Host = "webethicssolutions.com";
			$mail->Port =587;
			$mail->Username = "php@webethicssolutions.com";
			$mail->Password = "el*cBt#TuRH^"; */
			  
			  //Client SMTP 
			/* $mail->Host = "mail.mgdsw.info";
			$mail->Port =587;
			$mail->Username = "cdr@mgdsw.info";
			$mail->Password = "+UI4cK~Jq2D@bFIB";  */
			
			
			
			if($from!='')
			 $mail->From = $from;
		     else
			 $mail->From = $setting[0]->from_email ;
		 
			if($fromname!='')
			 $mail->FromName = $fromname;
		     else
			 $mail->FromName = $setting[0]->from_name;
			
			$mail->AddAddress($to);
			$mail->IsHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $message;
			//$mail->addReplyTo(‘examle@examle.net’, ‘Information’);
			//$mail->addBCC(‘examle@examle.net’);
			//$mail->addAttachment(‘/home/kundan/Desktop/abc.doc’, ‘abc.doc’); // Optional name
			$mail->SMTPOptions= array(
			'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
			)
			);

			$mail->send();
			return true ;
		}catch (phpmailerException $e) {
				dd($e);
		} catch (Exception $e) {
				dd($e);
		}
		 return false ;
   }
// TOKEN 
	function getToken($length='')
	{
		if($length=='')
			$length =20;
		
		    $token = "";
		    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		    $codeAlphabet.= "0123456789";
		    $max = strlen($codeAlphabet); // edited

		    for ($i=0; $i < $length; $i++) {
		        $token .= $codeAlphabet[rand(0, $max-1)];
		    }

		    return $token;
	}
	

// GET THE IP ADDRESS 
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
  return $ipaddress;
}

// Show Site Title and LOGO 
function showSiteTitle($title){
	$setting = Setting::where('id',1)->first();
	
	if($setting && $title == 'title'){
		if($setting->site_title && $setting->site_title != ''){
			return $setting->site_title;
		}else{
			return trans('global.site_title');
		}
	}else if($setting && $title == 'logo'){
		if($setting->site_logo && $setting->site_logo != ''){

			return url('uploads/logo/'.$setting->site_logo);
		}else{
			return url('/img/logo.svg');
		}
	}
}

function urlsafe_b64decode($string)
{
	$ciphering = "AES-128-CTR";
	$decryption_key = "GeeksforGeeks";
	$options = 0;
	$iv_length = openssl_cipher_iv_length($ciphering);
	$decryption_iv = '1234567891011121';
	return openssl_decrypt ($string, $ciphering,$decryption_key, $options, $decryption_iv);
}

/* Function For the image */ 
function timthumb($img,$w,$h){

		  $user_img =  url('plugin/timthumb/timthumb.php').'?src='.$img.'&w='.$w.'&h='.$h.'&zc=1&q=90';

		  return $user_img ;

}

function list_states(){
	$statesData = StateList::all();
	return $statesData;
}

function list_companies(){
	$CompanyData = Company::all();
	return $CompanyData;
}

function list_plans(){
	$planData = Plan::all();
	return $planData;
}

function relationsArray(){
	//$array = array();
	$array = array(
				'wife'=>'Wife',
				'husband'=>'Husband',
				'daughter'=>'Daughter',
				'son'=>'Son',
				'mother'=>'Mother',
				'father'=>'Father',
				'brother'=>'Brother', 	
				'sister'=>'Sister',
			);

	return $array;			
}
function birth_years(){
	$birth_years = collect(range(12, 5))->map(function ($item) {
		return (string) date('Y') - $item;
	});
	return $birth_years;
}
function getStateNameByStateId($state_id){
	$state_name = '';
	$getname = StateList::where('id',$state_id)->select('state_name')->first();
	if(!is_null($getname) && ($getname->count())>0)
		$state_name = $getname->state_name;
	return $state_name;
}
function getDistrictNameByDistrictId($district_id){
	$district_name = '';
	$getname = CityLists::where('id',$district_id)->select('city_name')->first();
	if(!is_null($getname) && ($getname->count())>0)
		$district_name = $getname->city_name;
	return $district_name;
}
function viewDateFormat($date){
	return Carbon::parse($date)->format(config('constant.FRONT_DATE_FORMAT'));
}
function pr($data){

  echo "<pre>";
  print_r($data);
  echo "</pre>" ;die;
}
/*
*Display policy number
*/
function generatePolicyNumber($userId){
	$policyId = '#'.str_pad($userId, 8, '0', STR_PAD_LEFT);
	return $policyId;
}

/*
* Function that return unread message count of login User by particular user groups
*/
function unreadMessageCount($user_id){
	$unread = 0;
	$user = User::with('group')->where('id',$user_id)->first();
	$loginPersonId = auth()->id();

	$unreadIds = MessageStatus::select(\DB::raw('`group_id` as group_id, count(`group_id`) as messages_count'))
            ->where('user_id', $loginPersonId)
            ->where('read_unread', 0)
            ->groupBy('group_id')
            ->get();

	if($user && isset($user->group) && count($user->group) > 0){
		foreach ($user->group as $key => $group) {
        	$contactUnread = $unreadIds->where('group_id', $group->id)->first();
        	if($contactUnread){
        		$unread += $contactUnread ? intval($contactUnread->messages_count) : 0;
        	}
        }
	}
	return $unread;
}
// ForWebinarTimeConverstion
const SECONDS_PER_MINUTE = 60;
const SECONDS_PER_HOUR = 60 * SECONDS_PER_MINUTE;
const SECONDS_PER_DAY = 24 * SECONDS_PER_HOUR;

const DAYS_PER_YEAR = 365;
const DAYS_PER_LEAP_YEAR = DAYS_PER_YEAR + 1;

const EPOCH_MONTH = 1;
const EPOCH_YEAR = 1970;

function getDateTime(int $timestamp) : array
{
    $days = intdiv($timestamp, SECONDS_PER_DAY);

    $year = EPOCH_YEAR;
    while ($days >= getDaysForYear($year)) {
        $days -= getDaysForYear($year);
        $year++;
    }

    $daysPerMonth = getDaysPerMonth($year);
    $month = EPOCH_MONTH;
    while ($days >= $daysPerMonth[$month]) {
        $days -= $daysPerMonth[$month];
        $month++;
    }

    $day = $days + 1;

    $secondsRemaining = $timestamp % SECONDS_PER_DAY;
    $hour = intdiv($secondsRemaining, SECONDS_PER_HOUR);
    $minute = intdiv($secondsRemaining, SECONDS_PER_MINUTE) % SECONDS_PER_MINUTE;
    $second = $secondsRemaining % SECONDS_PER_MINUTE;

    return [
        'year' => $year,
        'month' => $month,
        'day' => $day,
        'hour' => $hour,
        'minute' => $minute,
        'second' => $second
    ];
}

function isLeapYear(int $year) : bool
{
    return $year % 400 === 0 || ($year % 4 === 0 && $year % 100 !== 0);
}

function getDaysForYear(int $year) : int {
    return isLeapYear($year) ? DAYS_PER_LEAP_YEAR : DAYS_PER_YEAR;
}

function getDaysPerMonth(int $year) : array
{
    return [0, 31, isLeapYear($year) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
}

function extractDate($timestamp){

	$date = date('d/m/Y', $timestamp);
	$unixDate = strtotime($date);
	return $unixDate;
	// var_dump($unixDate);exit;
}
function extractTime($timestamp){
	$time = date('H:i:s', $timestamp);
	$unixTime = strtotime($time);
	return $unixTime;

}
	

// forCheckingUser-WebinarRegistrations

 function isRegistered($webinar_id){
	$user_id = Auth()->user()->id;
			$registerdUesr = WebinarRegistraion::where('webinar_id',$webinar_id)->where('user_id',$user_id)->first();
		
		// CheckingUserRegisteredStatus
		
		$isRegistered=0;
		if($registerdUesr!=null){
		 		$isRegistered=1;
		}
		return $isRegistered;
}
	
//RemainingTimeCountForWebinars
function RemainingTimeCount($webinarEndData){
	$now = new DateTime();
	$future_date = new DateTime(date('Y-m-d H:i:s',$webinarEndData));
	
	$interval = $future_date->diff($now);


return $interval->format("%a d, %h h, %i m, %s s"); 

}