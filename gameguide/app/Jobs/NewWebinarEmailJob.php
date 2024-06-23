<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NewWebinarEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Webinarkey;
use App\Models\Webinar;
use App\Models\Notification;
use App\Notifications\WebinarNotifications;




use App\Models\User;

class NewWebinarEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $webinar;
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $newWebinars = Webinar::where('status',0)->get();
        if(!empty($newWebinars)){
             $status = "We invite you to register for this webinar.";
             $subject = "Webinar Annoucment";
            // $allUsers = User::where('email_status',1)->get();
             $allUsers = User::get();

            
             foreach($newWebinars as $web){

                     if(!empty($allUsers)){
                        foreach($allUsers as $key=>$user){
                            
                            // ForHeaderNotifications
                            $user->notify(new WebinarNotifications($web,4,'New Webinar'));
                             $userNofitication=$user->notifications()->get()->first();
                             $userNofitication->webinar_id=$web->id;
                             $userNofitication->save();
                             if($user->email_status==1){
                                Mail::to($user->email)->send(new NewWebinarEmail($web,$user->full_name,$status,$subject,0));
                            }
                         }
                     }
                     
         Webinar::where('id', $web->id)->update(['status' => 1]);
 
        }
     }
    }
    public function failed(\Throwable $exception)
    {
    //    dd($exception);
    }
}
