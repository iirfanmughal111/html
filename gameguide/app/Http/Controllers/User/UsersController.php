<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\UpdateUserProfile;
use App\Http\Requests\Frontend\UpdateUserPassword;

use App\Http\Requests\Frontend\UploadProfilePhoto;
use App\Http\Requests\Frontend\UploadBanner;
use App\Http\Requests\Frontend\UpdateSocailLinkRequest;
use App\Http\Requests\Frontend\UpdateDescriptionRequest;

use App\Http\Requests\Frontend\EditProfileNameRequest;

use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\EmailTemplate;
use App\Models\TempRequestUser;
use App\Models\Game;
use App\Http\Requests\CreateUserRequest;
use League\Csv\Writer;	
use Auth;
use Config;
use Response;
use Hash;
use DB;
use DateTime;
use Session;
use Carbon\Carbon;

class UsersController extends Controller
{
	//Records per page 
	protected $per_page;
	private $qr_code_path;
	public function __construct()
    {
	    
        $this->per_page = Config::get('constant.per_page');
		$this->report_path = public_path('/uploads/users');
    }
	
	public function editProfile()
    {
    	//$games = Game::orderBy('created_at', 'desc')->get();
    	$games = Game::orderBy('position', 'asc')->get();
    	$role_id = Config::get('constant.role_id');
    	//$coaches = User::where('role_id',$role_id['COACHE_USER'])->get();
    	$query = User::where('role_id',$role_id['COACHE_USER'])->where('status','1');
        if (Auth::check()) {
            $loginUserId = Auth::user()->id;

            $query->where('id','!=',$loginUserId);
        }
        $coaches = $query->get();
		//pr($coaches);
    	/*$user = User::where('id',Auth::user()->id)->first();*/
		return view('frontend.creaters.account.account',compact('games','coaches'));
		
    }
	
	public function member(){
		
		return view('frontend.creaters.account.member');
	}
	/*==================================================
	UPDATE USER PROFILE 
==================================================*/ 	
	public function UpdateEditProfile(UpdateG $request)
	{
		if($request->ajax()){
			$request->first_name;
			$id = auth::user()->id; 
			$data=array(
			'first_name'=>$request->first_name,
			'last_name'=>$request->last_name,
			);
			
			//check if other user take this name or not 
			$user = User::where('id','==',$id)->get();
			if(count($user) <=0){
				if($request->user_bio)
					$data['user_bio']=$request->user_bio;
					User::where('id',$id)->update($data);
					$result = array('success'=>true);
			}else{
				 $result = array('success'=>false);
			}	
			return Response::json($result, 200);
		}
    }
	
	public function landing_page()
    {
    	return view('frontend.creaters.landing.landing');
    }
    
	//VERIFY ACCOUNT  
	public function verifyAccount($token)
    {
		
		$result = User::where('verify_token', '=' ,$token)->get();
		$notwork =false; 
		if(count($result)>0){
			if($result[0]->created_by == 0){
				$userUpdate = User::where('email',$result[0]->email);
				$data['verify_token'] =NULL;			
				$data['status'] =1;		
				$data['created_by'] = 1;
				$userUpdate->update($data);
				return redirect('login')->with('success','Your account is verified.');	;
			}else{
				$url_post = url('password/reset_new_user_password');
				$notwork =true;  
				return view('auth.passwords.reset',compact('token','notwork','url_post'));	
			}
			
		}else{
			 return redirect('login')->with('error','Your Link is not correct to reset password.');	;
		}
		
		
        	
    }
	public function passwordUpdate(UpdateUserPassword $request)
    {
		// IF AJAX
		if($request->ajax()){
			$data=array();
			$userData = user_data();
			$user_id = auth::user()->id; 
			$userUpdate = User::where('id',$user_id);
			$newPassword=$request->password; //NEW PASSWORD
			$hashed = $userData->password;  //DB PASSWORD
	   
			if(Hash::check($request->old_password, $hashed)){
				$hashed = Hash::make($newPassword);
				
				$data['password'] = $hashed;			
				$userUpdate->update($data);
				$result =array(
				'success' => true
				);	
			}else{
				$result =array(
				'success' => false,
				'errors' => array('old_password'=>'Password does not match.')
				);	
			}
			return Response::json($result, 200);
		}
    }	
	
    public function uploadProfilePhoto(UploadProfilePhoto $request)
    {
		// IF AJAX
		if($request->ajax()){
			$user_data =user_data();
			$user_id =$user_data->id;
			/***** Upload Crop profile *******/
			$image_file = $request->upload_profile_crop_file;
			list($type, $image_file) = explode(';', $image_file);
			list(, $image_file)      = explode(',', $image_file);
			$image_file = base64_decode($image_file);
			$image_name= time().'_profile_'.rand(100,999).'.png';

			//CREATE REPORT FOLDER IF NOT 
			if (!is_dir($this->report_path)) {
			mkdir($this->report_path, 0777);
			}
			//CREATE USER ID FOLDER 
			$user_id_path = $this->report_path.'/'.$user_id;
			if (!is_dir($user_id_path)) {
			mkdir($user_id_path, 0777);
			}

			if($user_data->profile_photo != NULL)
				@unlink($user_id_path.'/'.$user_data->profile_photo);

			file_put_contents($user_id_path.'/'.$image_name, $image_file);

			/****** Upload Original Photo ********/
			$original_image = $request->file('upload_profile_file');
				
			$new_name = rand() . '_original_profile.' . $original_image->getClientOriginalExtension();

			//CREATE REPORT FOLDER IF NOT 
			if (!is_dir($this->report_path)) {
				mkdir($this->report_path, 0777);
			}
			//CREATE USER ID FOLDER 
			$user_id_path = $this->report_path.'/'.$user_id;
			if (!is_dir($user_id_path)) {
				mkdir($user_id_path, 0777);
			}
			
			if($user_data->profile_original_photo != NULL)
		 		@unlink($user_id_path.'/'.$user_data->profile_original_photo);

			$original_image->move($user_id_path, $new_name);

			/***** Check if coach image exist and not empty *******/
			if(isset($request->upload_coache_crop_file) && !empty($request->upload_coache_crop_file)){

				$coach_image_file = $request->upload_coache_crop_file;
				list($type, $coach_image_file) = explode(';', $coach_image_file);
				list(, $coach_image_file)      = explode(',', $coach_image_file);
				$coach_image_file = base64_decode($coach_image_file);
				$coache_image_name= time().'_coache_profile_'.rand(100,999).'.png';

				//CREATE REPORT FOLDER IF NOT 
				if (!is_dir($this->report_path)) {
				mkdir($this->report_path, 0777);
				}
				//CREATE USER ID FOLDER 
				$user_id_path = $this->report_path.'/'.$user_id;
				if (!is_dir($user_id_path)) {
				mkdir($user_id_path, 0777);
				}

				if(isset($user_data->userProfile)){
					if(!empty($user_data->userProfile->coache_photo))
						@unlink($user_id_path.'/'.$user_data->userProfile->coache_photo);
				}
				file_put_contents($user_id_path.'/'.$coache_image_name, $coach_image_file);

				$userProfile = UserProfile::updateOrCreate([
				    //Add unique field combo to match here
				    //For example, perhaps you only want one entry per user:
				    'user_id'   => $user_id,
				],[
				    'coache_photo' => $coache_image_name
				]);
			}
			
			//$image->move($user_id_path, $new_name);
			$userUpdate = User::where('id',$user_id);
			$data['profile_photo'] = $image_name;
			$data['profile_original_photo'] = $new_name;		
			$userUpdate->update($data);
			$path = url('uploads/users').'/'.$user_id.'/'.$image_name;

			//$image_url  =  timthumb($path,140,140);
			$image_url  =  $path;


			return response()->json([
			'success'=>true,
			'message' => 'Image Upload Successfully',
			'image_url'  => $image_url
			]);  
				
		}
    }

	
	public function uploadBannerPhoto(UploadBanner $request)
    {
		// IF AJAX
		if($request->ajax()){
				/*** Banner Original File ***/
				$image = $request->file('upload_banner_file');
				// pr($image->getClientOriginalName());
				//$document_type = $request->document_type;
				$new_name = rand() . '_original_banner.' . $image->getClientOriginalExtension();
				
				$user_data =user_data();
				$user_id =$user_data->id;
			
					
				//CREATE REPORT FOLDER IF NOT 
				if (!is_dir($this->report_path)) {
					mkdir($this->report_path, 0777);
				}
				//CREATE USER ID FOLDER 
				$user_id_path = $this->report_path.'/'.$user_id;
				if (!is_dir($user_id_path)) {
					mkdir($user_id_path, 0777);
				}
				
				if($user_data->banner_original_photo != NULL)
			 		@unlink($user_id_path.'/'.$user_data->banner_original_photo);

				$image->move($user_id_path, $new_name);

				/**** Upload Banner Crop Image ******/
				$banner_crop_file = $request->upload_banner_crop_file;
				if(!empty($banner_crop_file)){
					list($type, $banner_crop_file) = explode(';', $banner_crop_file);
					list(, $banner_crop_file)      = explode(',', $banner_crop_file);
					$banner_crop_file = base64_decode($banner_crop_file);
					$image_name= time().'_banner_'.rand(100,999).'.png';

					//CREATE REPORT FOLDER IF NOT 
					if (!is_dir($this->report_path)) {
						mkdir($this->report_path, 0777);
					}
					//CREATE USER ID FOLDER 
					$user_id_path = $this->report_path.'/'.$user_id;
					if (!is_dir($user_id_path)) {
						mkdir($user_id_path, 0777);
					}

					if($user_data->banner_photo != NULL)
						@unlink($user_id_path.'/'.$user_data->banner_photo);

					file_put_contents($user_id_path.'/'.$image_name, $banner_crop_file);
				}

				$userUpdate = User::where('id',$user_id);
				$data['banner_original_photo'] = $new_name;	
				$data['banner_photo'] = $image_name;			
			    $userUpdate->update($data);
				$path = url('uploads/users').'/'.$user_id.'/'.$image_name;
				
                //$image_url  =  timthumb($path,448,155);
                $image_url = $path;
				
				  return response()->json([
				   'success'=>true,
				   'message' => 'Image Upload Successfully',
				   'image_url'  => $image_url
				  ]); 
				
		}
    }

    /*Edit profile Name*/
    public function edit_profile_name(EditProfileNameRequest $request){
    	//If Ajax request
    	if($request->ajax()){
			$id = auth::user()->id; 
			$data = array(
				'first_name'=>$request->first_name,
				'last_name'=>$request->last_name,
				'email'=>$request->email,
			);
			$userUpdate = User::find($id);
			$userUpdate->update($data);

			$user = User::where('id',$id)->first();
			Auth::setUser($user);
            $className = $request->className;
			$view = view("frontend.partials.name_edit",compact('className'))->render();
			$success = true;

	        return Response::json(array(
			  'success'=>$success,
			  'data'=>$view
			 ), 200);

		}
    }

    /*Edit Profile Tag*/
    public function edit_profile_tag(Request $request){
    	//If Ajax request
    	if($request->ajax()){
			$id = auth::user()->id; 
			$data = array(
				'tag_line'=>$request->tag_line
			);
			$userUpdate = User::find($id);
			$userUpdate->update($data);

			$user = User::where('id',$id)->first();
			Auth::setUser($user);

			$className = $request->className;

			$view = view("frontend.partials.tag_edit",compact('className'))->render();
			$success = true;

	        return Response::json(array(
			  'success'=>$success,
			  'data'=>$view
			 ), 200);

		}

    }

    /*Edit Social Link*/
    public function editSocailLink(UpdateSocailLinkRequest $request){
    	$data = [];
        $data['success'] = false;
        $data['message'] = 'Invalid Request';
    	if(!empty(trim($request->user_id)) || !empty(trim($request->facebook_link)) || !empty(trim($request->instagram_link)) || !empty(trim($request->twitter_link))){
    		$stored_data = User::with('userProfile')->where('id',$request->user_id)->first();
    		if(!empty($stored_data)){
    			$profileData = array();
				
				if(!empty(trim($request->facebook_link)))
					$profileData['facebook_link'] = trim($request->facebook_link);

				if(!empty(trim($request->instagram_link)))
					$profileData['instagram_link'] = trim($request->instagram_link);

				if(!empty(trim($request->twitter_link)))
					$profileData['twitter_link'] = trim($request->twitter_link);

				if(isset(($stored_data->userProfile))){
					$profileDataMain = UserProfile::find($stored_data->userProfile->id);
					$profileDataMain->update($profileData);
				}else{
					$user_id = trim($request->user_id);
					if(!empty($user_id))
						$profileData['user_id'] = $user_id;

					$profile = UserProfile::create($profileData);
				}
    		}
    		$className =  'profile-page';
    		$data['success'] = true;
        	$data['message'] = 'Successfully modify social link';
        	$data['view'] = view("frontend.partials.social_link_icons",compact('className'))->render();
    	}
    	return Response::json($data, 200);
    }

    /*Update description*/
    public function editDescription(UpdateDescriptionRequest $request){
    	$data = [];
        $data['success'] = false;
        $data['message'] = 'Invalid Request';
    	if(!empty(trim($request->user_id)) || !empty(trim($request->user_description))){
    		$stored_data = User::with('userProfile')->where('id',$request->user_id)->first();
    		if(!empty($stored_data)){
    			$profileData = array();
				
				if(!empty(trim($request->user_description)))
					$profileData['description'] = trim($request->user_description);


				if(isset(($stored_data->userProfile))){
					$profileDataMain = UserProfile::where('id',$stored_data->userProfile->id);
					$profileDataMain->update($profileData);
				}else{
					$user_id = trim($request->user_id);
					if(!empty($user_id))
						$profileData['user_id'] = $user_id;

					$profile = UserProfile::create($profileData);
				}
    		}
    		$data['success'] = true;
        	$data['message'] = 'Successfully modify Description';
    	}
    	return Response::json($data, 200);
    }

    public function checkRegister(CreateUserRequest $request){
    	$data = [];
    	$data['success'] = true;
        $data['message'] = 'Successfully modify Description';
        return Response::json($data, 200);
    }
	
	
   //logout 	
   public function logout()
    {
		 \Auth::logout();
		 Session::put('is_admin_login', '');
		 return redirect('login');
		
    }
	function password_generate($chars) 
	{
	  $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
	  return substr(str_shuffle($data), 0, $chars);
	}
  
}
