<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Webinar;
use App\Models\Notification;
use Carbon\Carbon;
use DB;
class ExitWebinarNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $expredTime = Carbon::now()->subDays(1);

        $nofitfyExit = Notification::where('read_at','<=',$expredTime)->whereNotNull('webinar_id')->get();
       

        if(!empty($nofitfyExit)){
            foreach($nofitfyExit as $notif){
                   $notif->delete();
              
            }
            
        }
   
    }
    public function failed(\Throwable $exception)
    {
       //dd($exception);
    }
}
