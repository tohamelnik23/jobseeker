<?php
namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\PaymentProcessing;
use App\Console\Commands\HourlyEscrow;
use App\Console\Commands\HourlyProcessing;
use App\Console\Commands\InterestingJobs;
use App\Console\Commands\JobExpired;
use App\Console\Commands\JobExpiring;
use App\Console\Commands\OfferExpiring;
use App\Console\Commands\OfferExpired;
use App\Console\Commands\ShiftExpired;
class Kernel extends ConsoleKernel{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
            PaymentProcessing::class,
            HourlyEscrow::class,
            HourlyProcessing::class,
            InterestingJobs::class,
            JobExpired::class,
            JobExpiring::class,
            OfferExpiring::class,
            OfferExpired::class,
            ShiftExpired::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule){
        // $schedule->command('inspire')->hourly();
        $schedule->command('payment:processing')->dailyAt('08:00'); 
        $schedule->command('payment:escrow')->dailyAt('08:00'); 
        $schedule->command('payment:hourly')->dailyAt('08:00');
        $schedule->command('jobs:interesting')->dailyAt('12:00');
        $schedule->command('job:expired')->hourly();
        $schedule->command('job:expiring')->hourly();
        $schedule->command('offer:expiring')->hourly();
        $schedule->command('offer:expired')->hourly();
        $schedule->command('shift:expired')->everyMinute();
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(){
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}