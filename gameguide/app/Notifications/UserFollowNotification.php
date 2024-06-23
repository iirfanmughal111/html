<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserFollowNotification extends Notification
{
    use Queueable;

    public $user;

    public $status;

    public $imagePath;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$status,$imagePath)
    {

        $this->user=$user;
        $this->status=$status;
        $this->imagePath=$imagePath;
    
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

   
    public function toArray($notifiable )
    {

        return [

            'user_id'=>$this->user['id'],
            'first_name'=>$this->user['first_name'],
            'last_name'=>$this->user['last_name'],
            'status' => $this->status,
            'imagePath'=>$this->imagePath

        ];
    }

}
