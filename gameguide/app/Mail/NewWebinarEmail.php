<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewWebinarEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($webinar,$username,$status,$subject,$coach)
    {
        //
        $this->webinar=$webinar;

        // $this->start_date=$start_date;

        // $this->start_time=$start_time;
        // $this->end_date=$end_date;
        // $this->end_time=$end_time;
        $this->username=$username;
        $this->subject=$subject;
        $this->coach=$coach;

        // $this->webinar_id=$webinar_id;
        $this->status=$status;
        // $this->keypoints=$keypoints;


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->view('admin.webinar.emailTemplates.newWebinarEmail',['username'=>$this->username,'webinar'=>$this->webinar,'status'=>$this->status,'coach'=>$this->coach])->subject($this->subject);
        
    }
}
