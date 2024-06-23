<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\webinarJob;
use App\Jobs\NewWebinarEmailJob;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\updateCancelPolicyStatus::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('Cancelpolicy:status')->dailyAt('7:00');
        $schedule->command('UpgradePlanRequest:status')->dailyAt('6:00');

        // $schedule->job(new NewWebinarEmailJob())->everyTenMinutes();
        $schedule->job(new webinarJob())->everyMinute();
        $schedule->job(new NewWebinarEmailJob())->daily();
        $schedule->job(new NewWebinarEmailJob())->everyMinute();


        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
