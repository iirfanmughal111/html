<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WebinarNotifications extends Notification
{
    use Queueable;
    public $webinar,$status,$message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($webinar,$status,$message)
    {
        $this->webinar = $webinar;
        $this->status = $status;
        $this->message = $message;

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


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'webinar_id'=>$this->webinar['id'],
            'title'=>$this->webinar['title'],
            'status'=>$this->status,
            'message'=>$this->message,
        ];
    }
}