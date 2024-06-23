<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\SubscribeUserRequest;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageMedia;
use App\Events\NewMessage;
use App\Models\Subscriber;
use App\Models\Role;
use App\Models\Group;
use App\Models\MessageStatus;
use Auth;
use Session;
use Config;
use Response;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class ChatController extends Controller
{
    public function __construct()
    {
        
        $this->chat_path = public_path('/uploads/chats');
    }

    public function index(){
		

		 if(!empty(Session::get('chat_with_user_id'))){
             Session::forget('chat_with_user_id');
           
        }
        /*Check if admin user login*/
        //get super admin user Id
        $adminRoleId = Role::where('slug','super-admin')->pluck('id');


        //get first index of array
        if(count($adminRoleId) > 0){
            $adminRoleId = $adminRoleId[0];
        }
		
	
	//	var_dump(Session::get('chat_user_id'));exit;
		

        if(intval(Auth::user()->role_id) == intval($adminRoleId)){
            //Check which person chat to view
            $chatPersonId = 0;
            if(!empty(Session::get('chat_user_id')))
                $chatPersonId = Session::get('chat_user_id');

            $user = User::where('id',$chatPersonId)->first();
			
            return view('admin.chat.index',compact('user'));
        }else{
		
            return view('frontend.chat.index');
        }
    }

    public function userSubscibe(SubscribeUserRequest $request){
        $data = [];
        $data['success'] = false;
        $data['message'] = 'Invalid Request';
        if(!empty(trim($request->coache_id)) && auth::user()){
            $subscriber_id = auth()->id();
            $coache_id = trim($request->coache_id);
            /*Check is user subscribe or not, if not then add to subscribe table*/
            $subscribe = Subscriber::where('subscriber_by',$subscriber_id)->where('user_id',$coache_id)->first();
            if($subscribe){
                $data['success'] = true;
                $data['message'] = 'Already subscribe user';
            }else{
                //check if vice versa exist
                $subscribe = Subscriber::where('subscriber_by',$coache_id)->where('user_id',$subscriber_id)->first();
                if(!$subscribe){
                    //Create new Group and save data to group_user table
                    $group = Group::create(['name' => getToken()]);
                    //check if group created
                    $group_id = $group->id;

                    $users = collect([]);
                    $users->push($subscriber_id);
                    $users->push($coache_id);
                    //get super admin user Id
                    $adminRoleId = Role::where('slug','super-admin')->pluck('id');

                    //get first index of array
                    if(count($adminRoleId) > 0){
                        $adminRoleId = $adminRoleId[0];
                    }

                    $adminUser = User::where('id', '!=', $subscriber_id)->where('id', '!=', $coache_id)->where('role_id', '=',$adminRoleId )->pluck('id');

                    //get first index of array
                    if(count($adminUser) > 0){
                        foreach ($adminUser as $key => $value) {
                            $users->push($value);
                        }
                    }

                    $group->users()->attach($users);

                     //save to db
                    $newSub = new Subscriber;
                    $newSub->subscriber_by = $subscriber_id;
                    $newSub->user_id = $coache_id;
                    $newSub->group_id = $group_id;
                    $newSub->save();
                }


                $data['success'] = true;
                $data['message'] = 'Already subscribe user';
            }
        }
        return Response::json($data, 200);
    }

    public function get()
    {
        // get all users except the authenticated one
        //dd(Auth::user());
        $id = auth()->id();
        $loginRoleId = Auth::user()->role_id;
        $loginPersonId = auth()->id();

        $roleId = Role::where('slug','coach')->pluck('id');
        $adminRoleId = Role::where('slug','super-admin')->pluck('id');
        //get first index of array
        if(count($adminRoleId) > 0){
            $adminRoleId = $adminRoleId[0];
        }

        /*Check login user Role*/
        if(intval(Auth::user()->role_id) == intval($adminRoleId)){
            //Check which person chat to view
            if(!empty(Session::get('chat_user_id')))
                $id = Session::get('chat_user_id');
        }

        //get user list
        $subscriberUser= Subscriber::where('subscriber_by',$id)->pluck('user_id');
        $creatorsSubscribe = Subscriber::where('user_id',$id)->pluck('subscriber_by');

        // Converting object to associative array
        $subscriberUser = json_decode(json_encode($subscriberUser), true);
        $creatorsSubscribe = json_decode(json_encode($creatorsSubscribe), true);
        $users = array_unique(array_merge($subscriberUser,$creatorsSubscribe), SORT_REGULAR);

        //get group list
        $subscriberUserGroup= Subscriber::where('subscriber_by',$id)->pluck('group_id');
        $creatorsSubscribeGroup = Subscriber::where('user_id',$id)->pluck('group_id');

        // Converting object to associative array
        $subscriberUserGroup = json_decode(json_encode($subscriberUserGroup), true);
        $creatorsSubscribeGroup = json_decode(json_encode($creatorsSubscribeGroup), true);
        $groups = array_unique(array_merge($subscriberUserGroup,$creatorsSubscribeGroup), SORT_REGULAR);

        $contacts = User::with('group')->where('id', '!=', $id)->where('role_id', '!=',$adminRoleId )->whereIn('id',$users)->whereHas('group', function (Builder $query) use ($groups) {
                $query->whereIn('group_id',$groups);
            })->get();
		
		 if(!empty(Session::get('chat_with_user_id'))){
            $id = Session::get('chat_with_user_id');
            $contacts=User::where('id',$id)->get();
        }

        
        $unreadIds = MessageStatus::select(\DB::raw('`group_id` as group_id, count(`group_id`) as messages_count'))
            ->where('user_id', $id)
            ->where('read_unread', 0)
            ->groupBy('group_id')
            ->get();

        // add an unread key to each contact with the count of unread messages
        $contacts = $contacts->map(function($contact) use ($unreadIds,$groups) {
            /*$contactUnread = $unreadIds->where('sender_id', $contact->id)->first();*/
            $unread = 0;
            if($contact->profile_photo==NULL ||$contact->profile_photo=='')
                $contact->profile_photo ='';
              else{
	              $profile_photo =  profile_photo($contact->id);
	              $contact->profile_photo = timthumb($profile_photo,50,50); 
              }

            //check only valid group pass
            if(isset($contact->group) && count($contact->group) > 0){
                foreach ($contact->group as $key => $group) {
                    if(in_array($group->id, $groups)){
                        $contact->groupdata = $group;

                        $contactUnread = $unreadIds->where('group_id', $group->id)->first();

                        $unread = $contactUnread ? $contactUnread->messages_count : 0;
                        unset($contact->group); 
                    }
                }
            }

            $contact->unread = $unread;

            return $contact;
        });

        return response()->json($contacts);
    }

    public function getMessagesFor($id,Request $request)
    {
        $per_page = config('constant.PER_PAGE_PAGINATION');

        /*Want to view following user id*/
        $chatPersonId = auth()->id();
        $loginRoleId = Auth::user()->role_id;
        $loginPersonId = auth()->id();

        $adminRoleId = Role::where('slug','super-admin')->pluck('id');
        //get first index of array
        if(count($adminRoleId) > 0){
            $adminRoleId = $adminRoleId[0];
        }

        /*Check login user Role*/
        if(intval(Auth::user()->role_id) == intval($adminRoleId)){
            //Check which person chat to view
            if(!empty(Session::get('chat_user_id')))
                $chatPersonId = Session::get('chat_user_id');
        }

        $group_id = '';
        //fetch group id
        $subscriberUserGroup= Subscriber::where('subscriber_by',$chatPersonId)->where('user_id',$id)->pluck('group_id');
        if($subscriberUserGroup && count($subscriberUserGroup) > 0){
            $group_id = $subscriberUserGroup[0];
        }else{
            $createrUserGroup= Subscriber::where('subscriber_by',$id)->where('user_id',$chatPersonId)->pluck('group_id');
            if($createrUserGroup && count($createrUserGroup) > 0){
                $group_id = $createrUserGroup[0];
            }
        }
        
        //dd(Session::get('is_admin_login'));
        if(empty(Session::get('is_admin_login'))  && Session::get('is_admin_login') != 1){
            // mark all messages with the selected contact as read
            Message::where('from', $id)->where('to', auth()->id())->update(['read_unread' => 1]);
        }

        // get all messages between the authenticated user and the selected user
        if(!empty($group_id)){
            $messagesQuery  = Message::with('messageMedia')->where('group_id',$group_id);
        }else{
            $messagesQuery  = Message::with('messageMedia')->where(function($q) use ($id,$chatPersonId) {
                $q->where('from', $chatPersonId);
                $q->where('to', $id);
            })->orWhere(function($q) use ($id,$chatPersonId) {
                $q->where('from', $id);
                $q->where('to', $chatPersonId);
            });
        }

        $allMessages = $messagesQuery->get();
        // dd(DB::getQueryLog()); // Show results of log
        
        $messages = $messagesQuery->orderBy('created_at','DESC')->paginate($per_page);

        $message_count = count($allMessages);
        $page_count = ceil($message_count/$per_page);

         $data = [];
         $message = [];
        // $data['messages'];
        
        foreach($messages as $key=>$value){
            $user_data = user_data_by_id($value->from);
            if($value->from == $loginPersonId){
                $message[$key]['sent_recieved'] ='sent';  
				  $message[$key]['user_role_id'] = User::where('id', '=', $value->from)->value('role_id'); 
            }else{
               $message[$key]['sent_recieved'] ='received'; 
				 $message[$key]['user_role_id'] =User::where('id', '=', $value->from)->value('role_id');
            }
          
            if($user_data->profile_photo==NULL ||$user_data->profile_photo==''){
                $message[$key]['profile_photo'] ='';
             }
             else{
                  
              $profile_photo =  profile_photo($value->from);
              $message[$key]['profile_photo'] = timthumb($profile_photo,50,50); 
            }
            $message[$key]['first_name'] =$user_data->first_name;
            $message[$key]['last_name'] =$user_data->last_name;
            $message[$key]['id'] = $value->id;
            $message[$key]['from'] = $value->from;
            $message[$key]['to'] = $value->to;
            $message[$key]['group_id'] = $value->group_id;
            $message[$key]['text'] = $value->text;
            $message[$key]['read_unread'] = $value->read_unread;
            $message[$key]['sent_time'] = $value->sent_time; 
            $message[$key]['created_at'] = $value->created_at;
            $message[$key]['updated_at'] = $value->updated_at;
            $message[$key]['message_media'] = $value->messageMedia;

        }

        $data['messages'] = $message;
        $data['message_count'] = $message_count;
        $data['page_count'] = $page_count;
        
        return response()->json($data);
    }

    public function send(Request $request)
    {
        $chatPersonId = auth()->id();
        $group_id = NULL;

        if(isset($request->group_id) && !empty($request->group_id))
            $group_id = $request->group_id;


        $message = Message::create([
            'from' => $chatPersonId,
            'to' => $request->contact_id,
            'group_id' => $group_id,
            'text' => $request->text
        ]);

        $messageId = $message->id;

        /*get all user belong to this message group id*/
        $contacts = User::where('id', '!=', $chatPersonId)->whereHas('group', function (Builder $query) use ($group_id) {
                    $query->where('group_id',$group_id);
                })->pluck('id');
        /*create that entry on message status table*/
        if($contacts && count($contacts)>0){
            foreach ($contacts as $key => $user) {
                $messageStatus = MessageStatus::create([
                    'message_id' => $messageId,
                    'user_id' => $user,
                    'group_id' => $group_id
                ]);
            }
        }


        if(!empty($request->file('upload_post'))){
             $upload_files = $request->file('upload_post');
             $Valid_image=array('image/jpeg','image/png','image/jpg');
             $valid_video=array('video/wmv','video/mpeg','video/ogg','video/mp4','video/webm','video/3gp','video/quicktime');
             $audio=array('audio/mpeg','audio/x-wav','audio/ogg','application/octet-stream');
             //dd($upload_files);

            foreach($upload_files as $key=>$upload_post){
                
                // $imageName = microtime() . '.' . $image->extension();

                // $image->move(public_path('images'), $imageName);
                if(in_array($upload_post->getMimeType(),$Valid_image)){
                $post_type = "image";
                }
                if(in_array($upload_post->getMimeType(),$valid_video)){
                 $post_type = "video";
                }
                if(in_array($upload_post->getMimeType(),$audio)){
                 $post_type = "audio";
                }
                
                //$new_name = rand() . '.' . $upload_post->getClientOriginalExtension();
                $new_name = rand().'.'.$upload_post->getClientOriginalName();
                
                //$filename = $upload_post->getClientOriginalName();
                 //CREATE REPORT FOLDER IF NOT 
                if (!is_dir($this->chat_path)) {
                    mkdir($this->chat_path, 0777,true);
                }
                //CREATE USER ID FOLDER 
                $user_id_path = $this->chat_path.'/'.$chatPersonId;
                if (!is_dir($user_id_path)) {
                    mkdir($user_id_path, 0777,true);
                }
                //CREATE POST ID FOLDER 
                $post_id_path = $user_id_path.'/'.$messageId;
                if (!is_dir($post_id_path)) {
                    mkdir($post_id_path, 0777,true);
                }
                $upload_post->move($post_id_path, $new_name);
                
                //STORE MEDIA 
                $messageMedia = new MessageMedia;
                $messageMedia->media=$new_name;
                $messageMedia->message_id=$messageId;
                $messageMedia->upload_type=$post_type;
                $messageMedia->save(); 
            }  
        }
        
        $message = Message::with('messageMedia')->where('id',$messageId)->first();
        
        //ADD profile pic or first name charter 
        $user_data = user_data_by_id($chatPersonId);

        $message->sent_recieved ='sent';        
         if($user_data->profile_photo==NULL ||$user_data->profile_photo==''){
             $message->profile_photo    ='';
             $message->first_name   =$user_data->first_name;
         }
           else{      
              $profile_photo =  profile_photo($chatPersonId);
              $message->profile_photo = timthumb($profile_photo,50,50); 
              $message->first_name  =$user_data->first_name;
            }       

        broadcast(new NewMessage($message))->toOthers();

        return response()->json($message);
    }

    public function notificationMessage(){
        $id = auth()->id();
        $unreadMessaage = Message::with('senderInfo','messageMedia')->where('to', auth()->id())->where('read_unread', 0)->has('senderInfo')->orderBy('created_at', 'DESC')->get();
        if(count($unreadMessaage) > 0 ){
            $countBy = $unreadMessaage->countBy('from');
        }
        $count = Message::where('to', auth()->id())->where('read_unread', 0)->has('senderInfo')->count();

        $messages = [];
        $messages['count'] = $count;
        $messages['userId'] = $id;
        $messages['msg'] = [];
        if(count($unreadMessaage) > 0 ){
            foreach ($unreadMessaage as $key => $value) {
                /*check if sendInfo not null*/
                //if(!empty($value['senderInfo'])){
                    $id = $value['id'];
                    $from = $value['from'];
                    $to = $value['to'];
                    $text = $value['text'];
                    $messages_count = $countBy[$from];
                    $sent_time = $value['sent_time'];
                    $first_name = $value['senderInfo']['first_name'];
                    $last_name = $value['senderInfo']['last_name'];
                    if($value['senderInfo']['profile_photo']==NULL ||$value['senderInfo']['profile_photo']==''){
                        $pic    ='';
                    }
                    else{
                        $profile_photo =  profile_photo($value['senderInfo']['id']);
                        $pic = timthumb($profile_photo,50,50);  
                    }

                    /*if text empty then check message media*/
                    if(empty($text) && count($value['messageMedia']) > 0){
                        $imageCount = 0; $audioCount = 0; $videoCount = 0;
                        foreach ($value['messageMedia'] as $mKey => $media) {
                           if($media['upload_type'] == 'image')
                                $imageCount++;
                            elseif($media['upload_type'] == 'video')
                                $videoCount++;
                            elseif ($media['upload_type'] == 'audio') 
                                $audioCount ++;
                        }

                        if($imageCount > 0){
                            if(!empty($text))
                                $text .= ' , ';

                            $files = ($imageCount == 1)?'file':'files';
                            $text .= $imageCount.' image '.$files;
                        }

                        if($videoCount > 0){
                            if(!empty($text))
                                $text .= ' , ';
                            $files = ($videoCount == 1)?'file':'files';
                            $text .= $videoCount.' video '.$files;
                        }

                        if($audioCount > 0){
                            if(!empty($text))
                                $text .= ' , ';
                            $files = ($audioCount == 1)?'file':'files';
                            $text .= $audioCount.' video '.$files;
                        }

                        $text .= ' have been sent';

                    }

                    if(!array_key_exists($from,$messages['msg'])){  
                      $messages['msg'][$from] = array('id' => $id, 'from' => $from, 'to' => $to, 'text' => $text, 'sent_time' => $sent_time, 'first_name' => $first_name, 'last_name' => $last_name, 'pic' => $pic, 'messages_count' => $messages_count);
                    }
                //}
            }
            $messages['msg'] = array_slice($messages['msg'], 0, 4);
        }else{
            $messages['msg'] = [];
        }

        return response()->json($messages);
    }

    public function unreadcountStatus($id,$type='') {
        /*Check type*/
        /*if(empty(Session::get('is_admin_login'))  && Session::get('is_admin_login') != 1){*/
        if($type == 'group'){
            $result = MessageStatus::where('group_id', $id)->where('user_id', auth()->id())->update(['read_unread' => 1]);
        }else{
            $result = Message::where('from', $id)->where('to', auth()->id())->update(['read_unread' => 1]);
        }
            return $result;
        /*}*/
    }

    /*Function that return only latest message*/
    public function getLatestMessage(){
		
        //fetch latest message
        $roleId = Role::where('slug','coach')->pluck('id');

        $id = auth()->id();
        $loginPersonId = auth()->id();
        $loginRoleId = Auth::user()->role_id;


        $adminRoleId = Role::where('slug','super-admin')->pluck('id');
        //get first index of array
        if(count($adminRoleId) > 0){
            $adminRoleId = $adminRoleId[0];
        }

        /*Check login user Role*/
        if(intval(Auth::user()->role_id) == intval($adminRoleId)){
            //Check which person chat to view
            if(!empty(Session::get('chat_user_id')))
                $id = Session::get('chat_user_id');
        }
        /*$subscriberUser= Subscriber::where('subscriber_by',$id)->pluck('user_id');
        $creatorsSubscribe = Subscriber::where('user_id',$id)->pluck('subscriber_by');
        // Converting object to associative array
        $subscriberUser = json_decode(json_encode($subscriberUser), true);
        $creatorsSubscribe = json_decode(json_encode($creatorsSubscribe), true);
        $users = array_unique(array_merge($subscriberUser,$creatorsSubscribe), SORT_REGULAR);*/

        $subscriberUserGroup= Subscriber::where('subscriber_by',$id)->pluck('group_id');
        $creatorsSubscribeGroup = Subscriber::where('user_id',$id)->pluck('group_id');

        // Converting object to associative array
        $subscriberUserGroup = json_decode(json_encode($subscriberUserGroup), true);
        $creatorsSubscribeGroup = json_decode(json_encode($creatorsSubscribeGroup), true);
        $groups = array_unique(array_merge($subscriberUserGroup,$creatorsSubscribeGroup), SORT_REGULAR);

        $latestUser = User::where('id', '!=', $id)->whereHas('group', function (Builder $query) use ($groups) {
                    $query->whereIn('group_id',$groups);
                })->orderBy('id', 'DESC')->first();

        $lastMessage = Message::whereIn('group_id', $groups)->orderBy('id', 'DESC')->first();
        /*$latestUser = User::where('id','!=',$id)->whereIn('id',$users)->orderBy('id', 'DESC')->first();*/
        $latest = [];
        if(!empty($lastMessage)){
            $messageLastTime = $lastMessage->created_at;
            $userLastJoin = $latestUser->created_at;
            //dd(DB::getQueryLog());
            //dd($lastMessage);
            
            if(!empty($lastMessage)){
                $latest['id'] = $lastMessage->id;
                $latest['from'] = $lastMessage->from == $id ? $lastMessage->to : $lastMessage->from;
                $latest['sent_time'] = $lastMessage->sent_time;
                $latest['read_unread'] = $lastMessage->read_unread;
                $latest['top_user'] = '';
            }
            /*check if last user join greater than last message then show this user on top*/
            if($userLastJoin->gt($messageLastTime)){
               $latest['top_user'] = $latestUser->id;
            }/*else{
                echo $userLastJoin .'is less than '.$messageLastTime;
                print_r($users);
                echo $latestUser->email;
                dd('less');
            }*/
        }

        return response()->json($latest);

    }
}
