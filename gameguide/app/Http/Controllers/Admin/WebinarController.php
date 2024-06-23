<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\Mail;
use App\Jobs\webinarJob;
use App\Jobs\NewWebinarEmailJob;
use App\Jobs\EmailVerificationJob;


use Illuminate\Support\Facades\File;


use Illuminate\Http\Request;
use App\Models\Webinar;
use App\Models\Webinarkey;
use App\Models\WebinarNotification;
use App\Models\WebinarRegistraion;
use App\Models\Notification;




use App\Models\User;


use Session;
use Config;
use Response;


class WebinarController extends Controller
{	
	protected $per_page;
    public function __construct()
    {
	    
        $this->per_page = Config::get('constant.per_page');
    	$this->webinar_path = public_path('/uploads/webinar');
    }

    public function webinars(Request $request){
        access_denied_user('webinar_listing');

    	$webinars = Webinar::all();


    	$webinar_data = $this->webinar_search($request,$pagination=true);
		if($webinar_data['success']){
			$webinars = $webinar_data['webinars'];
			$page_number =  $webinar_data['current_page'];
			if(empty($page_number))
				$page_number = 10;
			
			if(!is_object($webinars)) return $webinars;
			if ($request->ajax()) {
				return view('admin.webinar.webinarsPagination', compact('webinars','page_number'))->render();
			}
			return view('admin.webinar.webinar',compact('page_number','webinars'));	
		}else{
			return $webinar_data['message'];
		}
		// return view('admin.webinar.webinar',compact('webinars'));	

        return view('admin.webinar.webinar');	
		
	}
	  public function webinar_search($request,$pagination)
	{
		
		$page_number = $request->page;
		// $page_number = 1000;
		
		
		$number_of_records =$this->per_page;
		$title = trim($request->title);
		$result = Webinar::where(`1`, '=', `1`);
			
		if($title !=''){
			$title_q = '%' . $request->title .'%';
			// check title 
			if(isset($title) && !empty($title)){
				$result->where('title','LIKE',$title_q);
			} 

		}
		
		if($pagination == true){
			$webinars = $result->orderBy('created_at', 'desc')->paginate($number_of_records);
		}else{
			$webinars = $result->orderBy('created_at', 'desc')->get();
		}
		
		
		$data = array();
		$data['success'] = true;
		$data['webinars'] = $webinars;
		$data['current_page'] = $page_number;
		return $data;
	}

    public function create(){
		access_denied_user('webinar_create');
		$coaches = User::where('role_id',3)->get();
	
		return view('admin.webinar.webinarForm',['coaches'=>$coaches]);
	}
    public function store(request $request){
	
 
        $data =array();

		$data['title']	= trim($request->title);
		$startTime = trim($request->startTime);
		$endTime = trim($request->endTime);

		$startTimeSecs = strtotime("1970-01-01 $startTime UTC");
		$endTimeSecs = strtotime("1970-01-01 $endTime UTC");

		$data['start_datetime']	= $startTimeSecs+trim(strtotime($request->startDate));
		$data['end_datetime'] =  $endTimeSecs+trim(strtotime($request->endDate));

		$data['webinar_link']	= trim($request->webinarlink);
		$data['streamKey']	= trim($request->streamkey);

		$data['description']	= trim($request->webinarDescription);

		$data['coach_user_id']	= trim($request->coach_id);

		$data['status'] = 0;

		$logo = $request->file('logo');
		$featurdImg = $request->file('featuredImg');

        $webinarkeys = $request->webinarkey;

		$notifications = array();
		
		$notifications['date'] = $request->notificationdate;
		$notifications['time'] = $request->notificationtime;
		$dat = Webinar::create($data);

		$webinar_id = $dat->id;
		$imagedata = array();
		$UpdateWebinar = Webinar::where('id',$webinar_id);
		if(!empty($logo)){

			$new_logo_name = rand() . '_webinar_logo';

			//CREATE Webinar FOLDER IF NOT 
			if (!is_dir($this->webinar_path)) {
				mkdir($this->webinar_path, 0777);
			}

			//CREATE Logos Folder inside Webinar FOLDER IF NOT 

            $logo_path = $this->webinar_path.'/logos';
			if (!is_dir($logo_path)) {
				mkdir($logo_path, 0777);
			}

			$logo->move($logo_path, $new_logo_name);

			
			$imagedata['logo_image'] = $new_logo_name;
			$imagedata['logo_original_image'] = $logo->getClientOriginalName();
			$imagedata['logo_mimes'] = trim($logo->getClientOriginalExtension());

		}


        if(!empty($featurdImg)){

			$new_featuredImg_name = rand() . '_webinar_featuredImg';


			//CREATE Webinar FOLDER IF NOT 
			if (!is_dir($this->webinar_path)) {
				mkdir($this->webinar_path, 0777);
			}

			//CREATE Featured Images Folder inside Webinar FOLDER IF NOT 

            $featuredImg_path = $this->webinar_path.'/featured_images';
			if (!is_dir($featuredImg_path)) {
				mkdir($featuredImg_path, 0777);
			}
			$featurdImg->move($featuredImg_path, $new_featuredImg_name);
            
			$imagedata['featuredImg_image'] = $new_featuredImg_name;
			$imagedata['featuredImg_original_image'] = $featurdImg->getClientOriginalName();
			$imagedata['featuredImg_mimes'] = trim($featurdImg->getClientOriginalExtension());
		   

		}
	
 		$UpdateWebinar->update($imagedata);

		$saveNotifications = $this->save_notifications($notifications,$webinar_id);
		$saveKey = $this->save_key($webinarkeys,$webinar_id);
		

		Session::flash('success', 'New Webinar has been Created.');

		$this->startingNotification($data['start_datetime'],$webinar_id);
		NewWebinarEmailJob::dispatch();

		return redirect('admin/webinar');
    }

    public function save_key($keyNotes,$webinar_id){
		if(!empty($keyNotes) && count($keyNotes)>0 && !empty($webinar_id)){
			foreach ($keyNotes as $key => $note) {
				if(!empty(trim($note))){
					$data = array();
					$data['webinar_id'] = $webinar_id;
					$data['content'] = trim($note);
					Webinarkey::create($data);
				}
			}
		}
		return true;
	}

    public function save_notifications($notifications,$webinar_id){

		if(!empty($notifications) && count($notifications)>0 && !empty($webinar_id)){
			
			$ArrayCount =  count($notifications['time']);
			for ($i=0;$i<=($ArrayCount-1) ;$i++) {
				if(!empty($notifications)){
					$data = array();
					$notetime = $notifications['time'][$i];
					$notificationTimeSecs = strtotime("1970-01-01 $notetime UTC");
					$notificationDateSecs = strtotime($notifications['date'][$i]);

					$data['webinar_id'] = $webinar_id;
					$data['notification_datetime'] = $notificationDateSecs+$notificationTimeSecs;
					$data['status'] = 0;

					WebinarNotification::create($data);
				}
			}
			
	
		}

		return true;
	}	

	public function startingNotification($startDateTime,$webinar_id){
		$time = $startDateTime-300;
		$webinarNotif = webinarNotification::where(['webinar_id'=>$webinar_id,'notification_datetime'=>$time])->first();
		if(!isset($webinarNotif)){
			$data = array();	
			$data['webinar_id'] = $webinar_id;
			$data['notification_datetime'] = $startDateTime-300;
			$data['status'] = 0;
			WebinarNotification::create($data);
		}
	}

	public function webinar_edit($id){
		
		access_denied_user('webinar_edit');

		$webinar = Webinar::where('id',$id)->first();

		$coaches = User::where('role_id',3)->get();

		$keys = Webinarkey::where('webinar_id',$id)->get();
		$notifications = WebinarNotification::where('webinar_id',$id)->get();


		
		return view('admin.webinar.webinarForm',compact('webinar','keys','coaches','notifications'));
	}

	/*Update Webinar*/
	public function webinar_update(request $request){

		$webinar_id = trim($request->webinar_id);
		$webinar = Webinar::where('id',$webinar_id)->first();
			if(!empty($webinar_id)){
			$data =array();
			$data['title']	= trim($request->title);
			$startTime = trim($request->startTime);
			$endTime = trim($request->endTime);
			$startTimeSecs = strtotime("1970-01-01 $startTime UTC");
			$endTimeSecs = strtotime("1970-01-01 $endTime UTC");
			$data['start_datetime']	= $startTimeSecs+trim(strtotime($request->startDate));
			$data['end_datetime'] =  $endTimeSecs+trim(strtotime($request->endDate));
			$data['webinar_link']	= trim($request->webinarlink);
			$data['description']	= trim($request->webinarDescription);
			$data['streamKey']	= trim($request->streamkey);
			$data['coach_user_id']	= trim($request->coach_id);
			$data['status'] = 0;
			$logo = $request->file('logo');
			$featurdImg = $request->file('featuredImg');
			$webinarkeys = $request->webinarkey;
			$notifications = array();
			$notifications['date'] = $request->notificationdate;
			$notifications['time'] = $request->notificationtime;
			if(!empty($logo)){
				$imagedata =array();
				$new_logo_name = rand() . '_webinar_logo';

				//CREATE Webinar FOLDER IF NOT 
				if (!is_dir($this->webinar_path)) {
					mkdir($this->webinar_path, 0777);
				}

				//CREATE Logos Folder inside Webinar FOLDER IF NOT 

				$logo_path = $this->webinar_path.'/logos';
				if (!is_dir($logo_path)) {
					mkdir($logo_path, 0777);
				}

				$logo->move($logo_path, $new_logo_name);

			// If need to delete Old Logo Imagelogo_image
				
				$old_logo = $logo_path.'/'.$webinar->logo_image;
				
				if(isset($webinar->logo_image)){		

					$old_logo = $logo_path.'/'.$webinar->logo_image;	
					if (file_exists($old_logo)) {
							unlink($old_logo);
		
					}
				}

				$imagedata['logo_image'] = $new_logo_name;
				$imagedata['logo_original_image'] = $logo->getClientOriginalName();
				$imagedata['logo_mimes'] = trim($logo->getClientOriginalExtension());
				$webinar->update($imagedata);


			}

			if(!empty($featurdImg)){
				$imagedata =array();

				$new_featuredImg_name = rand() . '_webinar_featuredImg';

				//CREATE Webinar FOLDER IF NOT 
				if (!is_dir($this->webinar_path)) {
					mkdir($this->webinar_path, 0777);
				}

				//CREATE Featured Images Folder inside Webinar FOLDER IF NOT 
				
				$featuredImg_path = $this->webinar_path.'/featured_images';
				if (!is_dir($featuredImg_path)) {
					mkdir($featuredImg_path, 0777);
				}

				$featurdImg->move($featuredImg_path, $new_featuredImg_name);

					// If need to delete Old FeaturedImage Image
				if(isset($webinar->featuredImg_image)){		
				
					$old_featuredImg = $featuredImg_path.'/'.$webinar->featuredImg_image;
					if (file_exists($old_featuredImg)) {
						unlink($old_featuredImg);
						
					}
				}

				
				$imagedata['featuredImg_image'] = $new_featuredImg_name;
				$imagedata['featuredImg_original_image'] = $featurdImg->getClientOriginalName();
				$imagedata['featuredImg_mimes'] = trim($featurdImg->getClientOriginalExtension());
				$webinar->update($imagedata);


			}
				$webinar->update($data);

				//Deleting old key data 

				Webinarkey::where('webinar_id',$webinar_id)->delete();
				//Deleting old Notifications data 

				WebinarNotification::where('webinar_id',$webinar_id)->delete();

				if ($notifications['date']!=null || $notifications['time']!=null){
				$saveNotifications = $this->save_notifications($notifications,$webinar_id);
			}

				$saveKey = $this->save_key($webinarkeys,$webinar_id);
				Session::flash('success', 'Webinar edit successfully.');
				
				$this->startingNotification($data['start_datetime'],$webinar_id);

		return redirect('admin/webinar/edit/'.$webinar_id);
	}else{
			Session::flash('success', 'Something went wrong, please try again.');
		}
	
}



	public function delete_webinar(Request $request){
		access_denied_user('webinar_delete');
		$webinar_id = $request->webinar_id;
		$main_webinar  = Webinar::where('id',$webinar_id)->first();
		if (!$webinar_id){
			Session::flash('success', 'Something went wrong. Please try again.');

		return redirect('admin/webinar');

		}
		// deletinAllLinkedData
		Webinarkey::where('webinar_id',$webinar_id)->delete();
		WebinarNotification::where('webinar_id',$webinar_id)->delete();
		WebinarRegistraion::where('webinar_id',$webinar_id)->delete();
		Notification::where('webinar_id',$webinar_id)->delete();
		

		// deleting Old Logo Image
		$logo_path = $this->webinar_path.'/logos/';

		if(isset($main_webinar->logo_image)){		

		$old_logo = $logo_path.$main_webinar->logo_image;	
			if (file_exists($old_logo)) {
				unlink($old_logo);
			}
		}
		
		
		// deleting Old FeaturedImage Image
		$featuredImg_path = $this->webinar_path.'/featured_images/';
		if(isset($main_webinar->featuredImg_image)){		

			$old_featuredImg = $featuredImg_path.$main_webinar->featuredImg_image;
			if (file_exists($old_featuredImg)) {
				unlink($old_featuredImg);
				
			}
		}

		$main_webinar->delete();

		return redirect('admin/webinar');
		
		}

}