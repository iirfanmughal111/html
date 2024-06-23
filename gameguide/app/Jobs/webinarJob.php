<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Mail\webinarEmailNotification;
use App\Mail\WebinarCoachEmail;
use App\Mail\NewWebinarEmail;

use Illuminate\Support\Facades\Mail;

use App\Models\Wy;
use App\Models\Webinar;
use App\Models\Message;
use App\Models\WebinarNotification;
use App\Models\WebinarRegistraion;
use App\Models\Webinarkey;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use App\Models\Notification;
use App\Notifications\WebinarNotifications;
use DB;
use Illuminate\Support\Facades\Auth;

class webinarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    
        $DatetimeNow = new DateTime(Carbon::now());
        $dateNow = $DatetimeNow->format('Y/m/d');
        $timeNow = $DatetimeNow->format('H:i:s');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
   
        $current_time = \Carbon\Carbon::now()->timestamp;
        
        $unSentNotifications = WebinarNotification::where('status',0)->get();
        foreach ($unSentNotifications as $key=>$notif){
            if ($notif->notification_datetime<= $current_time){
       
                    $webinar = Webinar::where('id',$notif->webinar_id)->first();
                    if(!empty($webinar)){
                    $this->UserNotificications($webinar);
                    $this->HostNotifications($webinar,$notif->id);

                    }
        
                }
            }
        }

    // }


    public function UserNotificications ($webinar){
                $registeredUsers = WebinarRegistraion::where('webinar_id',$webinar->id)->get();
   
                    if (!empty($registeredUsers)){

                            foreach($registeredUsers as $user){

                                 Mail::to($user->user_email)->send(new NewWebinarEmail($webinar,$user->user_full_name,'Reminder!!!','Webinar Reminder!!!',0));

                                //  NavBarNotifications
                                 $user->notify(new WebinarNotifications($webinar,4,RemainingTimeCount($webinar->end_datetime)." left"));
                                    $userNofitication=$user->notifications()->get()->first();
                                    $userNofitication->notifiable_id=$user->user_id;
                                    $userNofitication->webinar_id=$webinar->id;
                                    $userNofitication->save();
      
                                $this->chatNotification($webinar,$user->user_id);
                                
                            }
                }
        
    }
    function HostNotifications($webinar ,$notif_id){


        Mail::to($webinar->CoachDetial->email)->send(new NewWebinarEmail($webinar,$webinar->CoachDetial->full_name,'Reminder!!!','Webinar Reminder!!!',1));

        $this->chatNotification($webinar,$webinar->CoachDetial->id);

        // NavbarNortification
        $user = User::where('id',$webinar->CoachDetial->id)->first();
        $user->notify(new WebinarNotifications($webinar,4,RemainingTimeCount($webinar->end_datetime)." left"));
        $userNofitication=$user->notifications()->get()->first();
        $userNofitication->notifiable_id=$user->id;
        $userNofitication->webinar_id=$webinar->id;

        $userNofitication->save();

         // UpdateNotificationStatusHere
        WebinarNotification::where('id', $notif_id)->update(['status' => 1]);


    }
    function chatNotification($webinar,$user_id){
        $webinarStartDate = date('d/m/Y', $webinar->start_datetime);
        $webinarEndDate = date('d/m/Y', $webinar->end_datetime);
        $webinarStartTime = date('H:i:s', $webinar->start_datetime);
        $webinarEndTime = date('H:i:s', $webinar->end_datetime);
        $webinarTitle = $webinar->title;
        $message = "This is a webinar notification from admin you are registered for $webinarTitle webinar that is starting from $webinarStartDate at $webinarStartTime and will end on $webinarEndDate  at $webinarEndTime.";
            $data =array();
			$data['from']	= 1;
			$data['to']= $user_id;
			$data['text'] = $message;
			$data['group_id'] = 1;
			$data['read_unread'] = 0;
			Message::create($data);
            return true;
    }
    public function failed(\Throwable $exception)
{
   dd($exception);
}
}

