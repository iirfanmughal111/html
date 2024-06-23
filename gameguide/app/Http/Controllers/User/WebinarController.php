<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use App\Models\WebinarRegistraion;
use App\Models\WebinarNotification;
use App\Models\WebinarActiveUser;
use App\Models\User;
use App\Models\Webinarkey;

use App\Models\UserProfile;
use Carbon\Carbon;
use DateTime;

use App\Mail\webinarEmailNotification;
use App\Mail\NewWebinarEmail;

use App\Jobs\NewWebinarEmailJob;
use App\Jobs\EmailVerificationJob;


use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Session;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_LiveBroadcastSnippet;
use Google_Service_YouTube_LiveBroadcastStatus;
use Google_Service_YouTube_LiveBroadcast;
use Google_Service_YouTube_LiveStreamSnippet;
use Google_Service_YouTube_CdnSettings;
use Google_Service_YouTube_LiveStream;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class WebinarController extends Controller
{
    
	public function UserSideWebinars(request $request){

//response = Http::get('https://ui-avatars.com/api/?background=0D8ABC&color=fff');
$webinars = Webinar::all();


return view('card',compact('webinars'));

		
		
	

		$current_time = \Carbon\Carbon::now()->timestamp;
		$webinar_id = $request->webinar_id;
		if($webinar_id=='past'){
			$pastWebinars = Webinar::where('end_datetime','<=',$current_time)->orderBy('start_datetime','desc')->paginate(12);
		return view('frontend.webinar.pastWebinars',compact('pastWebinars'));
		}
		$UpcommingWebinars = Webinar::where('end_datetime','>=',$current_time)->orderBy('start_datetime','asc')->get();
		$pastWebinars = Webinar::where('end_datetime','<=',$current_time)->orderBy('start_datetime','desc')->limit(10)->get();
		if ($webinar_id==null){
			if (!empty($UpcommingWebinars[0])){
			$selectedWebinar  = $UpcommingWebinars[0];
		}
		else{
			$selectedWebinar = Webinar::orderBy('start_datetime','desc')->latest()->first();
		}
		}
		else{

		$selectedWebinar = Webinar::where('id',$webinar_id)->first();
			
			if( $selectedWebinar == NULL){
				if (!empty($UpcommingWebinars[0])){
					$selectedWebinar  = $UpcommingWebinars[0];
			//	Session::flash('success-'.$selectedWebinar->id, 'Record not found 1!!');
					
				}
				else{
					$selectedWebinar = Webinar::orderBy('start_datetime','desc')->latest()->first();
			//	Session::flash('success-'.$selectedWebinar->id, 'Record not found 2!!');
					
				}
				if(!empty($selectedWebinar)){
				Session::flash('success-'.$selectedWebinar->id, 'Record not found 3s!!');
				}
				else{
					Session::flash('success-emptySelectedWebinar', 'Record not found 4!!');
				}
			}

		}
		$isCoachPage = 0;

		return view('frontend.webinar.webinarDetail',compact('response','selectedWebinar','UpcommingWebinars','pastWebinars','isCoachPage'));

	}
	public function isExpired($started_date){
    	$current_time = \Carbon\Carbon::now()->timestamp;
		$expired = 0;
		if ($started_date<=$current_time){
			$expired = 1;
		}
		return $expired;

	}
	public function registerUserConfirmation($webinar_id){
		$user_id = Auth()->user()->id;
		
			$registerdUesrs = WebinarRegistraion::where('webinar_id',$webinar_id)->where('user_id',$user_id)->first();
		
		// CheckingUserRegisteredStatus
		
		$isRegistered=0;
		if($registerdUesrs!=null){
		 		$isRegistered=1;
		}
		return $isRegistered;
	}
	public function pastWebinars(){
    	$current_time = \Carbon\Carbon::now()->timestamp;

		$pastWebinars = Webinar::where('end_datetime','<=',$current_time)->orderBy('start_datetime','desc')->paginate(12);

		
    
			return view('frontend.webinar.pastWebinars',compact('pastWebinars'));
	}
	public function playWebinars($webinar_id){
		$webinar = Webinar::where('id',$webinar_id)->get();
		
		$expired = $this->isExpired($webinar[0]->start_datetime);
		if($webinar[0]->coach_user_id!=Auth()->user()->id){
			
			if ($expired == 1 ){
				return view('frontend.webinar.playWebinar',compact('webinar'));
			}
			$isRegistered=$this->registerUserConfirmation($webinar_id);

			if ($isRegistered==0 ){
				Session::flash('success-'.$webinar_id, 'Access Denied! you are not registered for this webinar');
				return redirect('webinars/'.$webinar_id);
				}
		}
		
		if ($expired==0){
			Session::flash('success-'.$webinar_id, 'Access Denied! Webinar is not started yet.');
			return redirect('webinars/'.$webinar_id);
		}

		return view('frontend.webinar.playWebinar',compact('webinar'));
	}
	public function userProfile($user_id){


		$user = User::where('id',$user_id)->first();
		$userProfile = UserProfile::where('user_id',$user_id)->first();

		return view('frontend.webinar.user_details',compact('user','userProfile'));

	}
	public function registration(request $request){
		
		$webinar_id = $request->webinar_id;
		$webinar = Webinar::where('id',$webinar_id)->get();
		$started_date = $webinar[0]->start_datetime;

		if(!empty($webinar_id)){
			$data =array();
			$data['webinar_id']	= $webinar_id;
			$data['user_id']= Auth()->user()->id;
			$data['user_full_name']= Auth()->user()->full_name;
			$data['user_email']= Auth()->user()->email;
			$data['registraion_date'] = trim(strtotime(date('Y-m-d', time())));
			$data['registraion_time'] = trim(strtotime(date('H:i:s', time())));
			WebinarRegistraion::create($data);
		//	$this->EmailNotification($webinar_id);
			Session::flash('success-'.$webinar_id, 'Congratulations! you registered successfully.');

			return redirect('webinars/'.$webinar_id);

		}
		else{
		Session::flash('success-'.$webinar_id, 'Something went wrong, please try again..');
		return redirect('webinars/'.$webinar_id);
		}

	}
	public function EmailNotification($webinar_id){
		$webinar = webinar::where('id',$webinar_id)->first();
		$userEmail= Auth()->user()->email;
		$username = Auth()->user()->full_name;
		$status = "You have successfully registered for this webinar.";
		$subject = "Successfuly registered for webinar";
		Mail::to($userEmail)->send(new NewWebinarEmail($webinar,$username,$status,$subject,0));

	}
	public function CancelRegistration(request $request){
		$webinar_id = $request->webinar_id;
		
		if (!empty($webinar_id)){
			$user_id = Auth()->user()->id;
			
			WebinarRegistraion::where('webinar_id',$webinar_id)->where('user_id',$user_id)->delete();
			Session::flash('success-'.$webinar_id, 'Successfully Unregistered..');

			return redirect('webinars/'.$webinar_id);
		}
		else {

			return redirect()->back();
		}


	}
	public function watchingCount(request $request){
		$webi_id = $request->webinar_id;
    	$current_time = \Carbon\Carbon::now()->timestamp;
		$webinar = Webinar::where('id',$webi_id)->first();
		$webinar_video_id = $webinar->webinar_link;
		$video_endpoint = config('services.youtube.api_video_endpoint');
		$yt_key = config('services.youtube.yt_api_key');
		
		$url = "$video_endpoint?part=liveStreamingDetails&id=$webinar_video_id&fields=items%2FliveStreamingDetails%2FconcurrentViewers&key=$yt_key";
		$response = Http::get($url);
		
		$liveStreamDetail = $response['items'][0];
		if (!empty($liveStreamDetail)){
			$count = $liveStreamDetail['liveStreamingDetails']['concurrentViewers'];
		}
		else {
			$count = 0;
		}

		// if ($current_time>$webinar->end_datetime){
		// return json_encode('expired');
		// }
		// $activeUsersList = WebinarActiveUser::where('webinar_id',$webi_id)->get();
		// $count = count($activeUsersList);
		// $data = array();
		// $data['count'] = $count;
		return $count;

	}
	public function coachWebinars(request $request){
		$current_time = \Carbon\Carbon::now()->timestamp;
$isCoachPage = 1;
		$coach_id = $request->coach_id;
		// if(Auth()->user()->role_id==3){
		// 	return redirect()->back();
		// }
		$UpcommingWebinars = Webinar::where('end_datetime','>=',$current_time)->where('coach_user_id',$coach_id)->orderBy('start_datetime','asc')->get();
		$pastWebinars = Webinar::where('end_datetime','<=',$current_time)->where('coach_user_id',$coach_id)->orderBy('start_datetime','desc')->limit(10)->get();
	
			if (!empty($UpcommingWebinars[0])){
			$selectedWebinar  = $UpcommingWebinars[0];

		}
		else{
			$selectedWebinar = Webinar::orderBy('start_datetime','desc')->latest()->first();
			if(empty($selectedWebinar)){
			Session::flash('success-emptySelectedWebinar', 'No webinars for you!!');
			}

		}
	

		$isCoachPage = 1;

		return view('frontend.webinar.webinarDetail',compact('selectedWebinar','UpcommingWebinars','pastWebinars','isCoachPage'));


	}
	public function webinarCommonDetail($webinar_id){
		$webinarData = array();
		$current_time = \Carbon\Carbon::now()->timestamp;
		$webinarData['UpcommingWebinars'] = Webinar::where('end_datetime','>=',$current_time)->orderBy('start_datetime','asc')->get();
		$webinarData['pastWebinars'] = Webinar::where('end_datetime','<=',$current_time)->orderBy('start_datetime','desc')->limit(10)->get();
		if ($webinar_id==null){
				if (!empty($webinarData['UpcommingWebinars'][0])){
				$webinarData['selectedWebinar']  = $webinarData['UpcommingWebinars'][0];
			}
			else{
				$webinarData['selectedWebinar'] = Webinar::orderBy('start_datetime','desc')->latest()->first();
			}
		}
		else{

			$webinarData['selectedWebinar'] = Webinar::where('id',$webinar_id)->get();
			$webinarData['selectedWebinar'] = $webinarData['selectedWebinar'][0];

		}

		return $webinarData;

	}
	public function WebinarDetails(request $request){
		$emailToValidate = 'iirfanmughal236@gmail.com';
		$api_key = 'ac87e4224d564b2b8d77f6594c2002eb';
		$request_endpoint = 'https://api.zerobounce.net/v2/validate?api_key='.$api_key.'&email=';
		$url = $request_endpoint.urlencode($emailToValidate);
		dd($url);
		exit;
		// Complete API Libraries and Wrappers can be found here: 
  // https://www.zerobounce.net/docs/zerobounce-api-wrappers/#api_wrappers__v2__php

  //set the api key and email to be validated
  $api_key = 'ac87e4224d564b2b8d77f6594c2002eb';
  $emailToValidate = 'iirfanmughal236@gmail.com';
  // use curl to make the request
  $url = 'https://api.zerobounce.net/v2/validate?api_key='.$api_key.'&email='.urlencode($emailToValidate);

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
  dd($json["status"]);
  exit;

		$video_endpoint = config('services.youtube.api_video_endpoint');
		$key = "ac87e4224d564b2b8d77f6594c2002eb";
		$email = "admin@admin.com";
		$url = "https://api.zerobounce.net/v2/validate?api_key=$key&email=$email&ip_address=156.124.12.145";
		$response = Http::get($url);
		
		dd($response);
		
		exit;
		$current_time = \Carbon\Carbon::now()->timestamp;
		$webinar_id = $request->webinar_id;
		
		$UpcommingWebinars = Webinar::where('end_datetime','>=',$current_time)->orderBy('start_datetime','asc')->get();
		$pastWebinars = Webinar::where('end_datetime','<=',$current_time)->orderBy('start_datetime','desc')->limit(10)->get();
		if ($webinar_id==null){
			if (!empty($UpcommingWebinars[0])){
			$selectedWebinar  = $UpcommingWebinars[0];
		}
		else{
			$selectedWebinar = Webinar::orderBy('start_datetime','desc')->latest()->first();
			}
		}
		else{

			$selectedWebinar = Webinar::where('id',$webinar_id)->get();
			$selectedWebinar = $selectedWebinar[0];

		}
		return view('frontend.webinar.webinarDetail',compact('selectedWebinar','UpcommingWebinars','pastWebinars'));

		// $webinarData = $this->webinarCommonDetail($webinar_id);
		// $UpcommingWebinars = $webinarData['UpcommingWebinars'];
		// $pastWebinars = $webinarData['pastWebinars'];
		// $selectedWebinar = $webinarData['selectedWebinar'];

		// return view('frontend.webinar.webinarDetail',compact('selectedWebinar','UpcommingWebinars','pastWebinars'));

	}

}