<?php


namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserFriend;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Notifications\UserFollowNotification;
use DB;
class UserFriendController extends Controller
{
    
    public function addfriend(Request $request,$id){

      if(Auth::user()){

          $notify_id=$request->input('notify_id');

        if($notify_id){

            $nodifyExist=$this->exitUserNotificationById($notify_id);
            
            if($nodifyExist){

              $nodifyExist->delete();
            }
        } 
       
        $object = new UserFriend;

        $object->user_request=Auth::user()->id;
        $object->user_accept=$id;

        $object->save();

        $user=User::where('id',$id)->first();
  
        $user->notify(new UserFollowNotification(Auth::user(),'0',Auth::user()->getProfilePhotoUrlAttribute()));
        
        $userNofitication=$user->notifications()->get()->first();

        $userNofitication->user_friend_id=$object->id;
        $userNofitication->save();
      
        if($request->ajax()){

          $returnHTML = view('frontend.playercommunity.mockuser')->with('user',  $user)->render();
    
          return (response()->json(['html'=>$returnHTML]));   
 
     
        }
        
        return redirect('players');
    
      }
    }

    public function acceptfriend(Request $request,$id){


      $userFriendAccept = UserFriend::
        where('user_request', '=',$id )
      ->where('user_accept', '=', Auth::user()->id)->first();

      if(!empty($userFriendAccept)){
        $userFriendAccept->status=1;
        $userFriendAccept->save();
        $nofitfyExit=$this->exitUserNotification($userFriendAccept->id);

        if($nofitfyExit){

          $nofitfyExit->delete();
        }
        $userAcceptNotify=User::where('id',$id)->first();
        $userAcceptNotify->notify(new UserFollowNotification(Auth::user(),'1',Auth::user()->getProfilePhotoUrlAttribute()));
        $userNofitication=$userAcceptNotify->notifications()->get()->first();
        $userNofitication->user_friend_id=$userFriendAccept->id;
        $userNofitication->save();
      }
      else{
        $this->deleteExtraNotification($id);
      }
         

      if($request->ajax()){

        $user=User::where('id',$id)->first();
        $returnHTML = view('frontend.playercommunity.mockuser')->with('user',  $user)->render();
        return (response()->json(['html'=>$returnHTML]));   
         
      }
      

      return redirect('players');

    }

    public function rejectfriendRequest(Request $request,$id){


        $rejectfriendRequest = UserFriend::
        where('user_request', '=',$id )
      ->where('user_accept', '=', Auth::user()->id)->first();
      if (!empty($rejectfriendRequest->id)){

      $notifyexit=$this->exitUserNotification($rejectfriendRequest->id);
        if($notifyexit){

          $notifyexit->delete();
          
      }
      if($rejectfriendRequest){
          
                  $rejectfriendRequest->delete();
          
                }

      }
      else{
        $this->deleteExtraNotification($id);
      }


      $rejectUser=User::where('id',$id)->first();

      $rejectUser->notify(new UserFollowNotification(Auth::user(),'2',Auth::user()->getProfilePhotoUrlAttribute()));
       
     if($request->ajax()){

          
        $user=User::where('id',$id)->first();
        $returnHTML = view('frontend.playercommunity.mockuser')->with('user',  $user)->render();
  
        return (response()->json(['html'=>$returnHTML]));   
        
       
   
      }

      return redirect('players');
    }

    public function cancelfriendRequest(Request $request ,$id){

        $userFriend = UserFriend::
          where('user_request', '=', Auth::user()->id)
        ->where('user_accept', '=', $id)->first();
    

        if($userFriend){
            $userFriend->delete();
        }
           
        if($request->ajax()){

          
          $user=User::where('id',$id)->first();
          $returnHTML = view('frontend.playercommunity.mockuser')->with('user',  $user)->render();
    
          return (response()->json(['html'=>$returnHTML]));   
          
         
     
        }
        
        return redirect('players');

    }

    public function unfriend(Request $request,$id){


        $userFriend = UserFriend::
        where('user_request', '=', Auth::user()->id)
      ->orwhere('user_accept', '=', Auth::user()->id)->first();
  

      if (!empty($userFriend->id)){
          $nofitfyExit=$this->exitUserNotification($userFriend->id);

          if($nofitfyExit){

            $nofitfyExit->delete();
          
          } 
          if($userFriend){
          
              $userFriend->delete();
          }
    }
    else{
     $this->deleteExtraNotification($id);
    }
     
    //$rejectUser=User::where('id',$id)->first();

    //$rejectUser->notify(new UserFollowNotification(Auth::user(),'2',Auth::user()->getProfilePhotoUrlAttribute()));
    

       
    if($request->ajax()){


      

        $returnHTML = view('frontend.playercommunity.mockuser')->with('user',  Auth::user())->render();

        return (response()->json(['html'=>$returnHTML]));   
        
     
 
    }
    
        return redirect('players');
    }
 

    public function exitUserNotification($frndId){

      $userNofitication = Notification::where('user_friend_id',$frndId)->first() ;

      return $userNofitication;
    }

    public function exitUserNotificationById($id){

      $userNofitication = Notification::where('id',$id)->first() ;


      return $userNofitication;
    }

    public function userNotifications(){

      $count="";
      $userlist="";

      $currenUserNotifications = DB::table('notifications')->where('notifiable_id',Auth::user()->id)->whereNull('read_at') ->get();
  // dd($currenUserNotifications);
      if(count($currenUserNotifications)){
        
         $userlist=$this->convertJsonDecode($currenUserNotifications);
         $count=count($currenUserNotifications);
      }

      if(!count($currenUserNotifications)){

        
        $readUserNotifications = DB::table('notifications')->where('notifiable_id',Auth::user()->id)->whereNotNull('read_at')->inRandomOrder()
        ->limit(5)->get();
        $count=0;
       
    
        $userlist=$this->convertJsonDecode($readUserNotifications);

      }
      
        
       $returnHTML = view('frontend.playercommunity.notify')->with('userlist', $userlist)->render();
       
      
      return (response()->json(['html'=>$returnHTML,'unreadcount'=>$count]));   
    }

    public function convertJsonDecode($currenUserNotifications){

      $userlist=array();

      foreach ($currenUserNotifications as $key=>$notification) {
         
        array_push($userlist,json_decode($notification->data,true));
        $userlist[$key]['id']=$notification->id;
       
        auth()->user()->unreadNotifications->where('id', $notification->id)->markAsRead();
     
      }

      
      return $userlist;

    }
  
}