<?php 
// any invite, any job edit, any offer, any message 
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Model\Notification;
use App\Model\Job;
 
class ShiftExpired extends Command{
    protected $description 	= 'This is for shift expired.';
    protected $signature 	= 'shift:expired';
    public function __construct(){
        parent::__construct();
    }
    public function handle(){
        $now            =   Carbon::now();
        $jobs           =   Job::where('jobs.status', 1)
                                ->where('jobs.type', 'shift')
                                ->where('job_date_time', '<=',  $now->toDateTimeString())
                                ->get();
        
        foreach($jobs as $job){
            if($job->duration_type == "after"){ 
                $job_date   =   Carbon::parse($job->shift_end_date_time , 'UTC')->subSeconds($job->duration);
                
            }
            else{ 
                $job_date   =   Carbon::parse($job->job_date_time , 'UTC')->addSeconds($job->duration);
            }

            

            if( $now->diffInSeconds($job_date, false) < 0){
                Notification::create([
                    'notifications_fromuser' 	=>  0,
                    'notifications_touser'		=>  $job->user_id,
                    'notifications_value'		=>  "The shift '" . $job->headline . "' is expired.",
                    'notification_ref'          =>  $job->serial,
                    'notifications_type'		=> 'gig_expired'
                ]);
                $job->closeJob();
            }
        } 
    }
}