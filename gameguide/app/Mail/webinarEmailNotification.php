<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class webinarEmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($start_date,$start_time,$end_date,$end_time,$username,$title)
    {
        //
        $this->start_date=$start_date;
        $this->start_time=$start_time;
        $this->end_date=$end_date;
        $this->end_time=$end_time;
        $this->username=$username;
        $this->title=$title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.webinar.emailTemplates.email',['username'=>$this->username,'start_date'=>$this->start_date,'start_time'=>$this->start_time,'end_date'=>$this->end_date,'end_time'=>$this->end_time,'title'=>$this->title])->subject('Test webinar email');
    }
}
