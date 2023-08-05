<?php 
// any invite, any job edit, any offer, any message 
namespace App\Console\Commands; 
use Illuminate\Console\Command;
use Config, Mail, DB;
use Carbon\Carbon;
use App\Model\MasterSetting;
use App\Model\DiscovergigTransaction;
use App\Model\Job;
use App\Model\Invite;
use App\Model\Message;
use App\Model\MessageList;
use App\Model\Offer;
use App\Model\JobSentWarning;
use App\Model\Notification;

class JobExpiring extends Command{
    protected $description 	= 'This is for gig expiring.';
    protected $signature 	= 'job:expiring';
    public function __construct(){
        parent::__construct();
    }
    public function handle(){
        $expire_warning_date    =   MasterSetting::getValue('expire_warning_date');
        $now                    =   Carbon::now()->subDays($expire_warning_date);
        $jobs                   =   Job::where('status', 1)
                                        ->where('updated_at', '<=', $now->toDateTimeString())
                                        ->where('type', 'gig')
                                        ->get();
        foreach($jobs as $job){
            $flag = 1;
            //check invite actions
            $invite = Invite::where('job_id', $job->id)
                            ->orderBy('updated_at', 'desc')
                            ->first();
            if(isset($invite)){
                if($invite->updated_at < $now->toDateTimeString())
                    $flag = 0;
            }
            //check offer actions
            if($flag){
                $offer  =   Offer::where('job_id', $job->id)
                                ->orderBy('updated_at', 'desc')
                                ->where('status', 0)
                                ->first();
                if(isset($offer)){
                    if($offer->updated_at < $now->toDateTimeString())
                        $flag = 0;
                }
            }
            //check message action
            if($flag){
                $contracts  =   Offer::where('job_id', $job->id)
                                    ->where('status', '<>', 0)               
                                    ->get();
                $offered_array  =   array(); 
                foreach($contracts as $contract){
                    //Get Message List
                    $messageList =   $contract->getMessageList();
                    if(isset($messageList)){
                        $last_message   =   Message::where('message_list', $messageList->id)
                                                ->orderBy("created_at", 'desc')
                                                ->first();
                        if(isset($last_message)){
                            if($last_message->updated_at < $now->toDateTimeString())
                                $flag = 0;
                        }
                    }
                }
            } 
            if($flag){
                $job_sent_warning   =   JobSentWarning::where('job_id', $job->id)
                                            ->where('type', 'job')
                                            ->where('deleted', 0)
                                            ->first();
                if(!isset($job_sent_warning)){
                    // send email
                    Notification::create([
                        'notifications_fromuser' 	=>  0,
                        'notifications_touser'		=>  $job->user_id,
                        'notifications_value'		=>  "The gig '" . $job->headline . "' will be expired soon.",
                        'notification_ref'          =>  $job->serial,
                        'notifications_type'		=> 'gig_expiring'
                    ]);
                    JobSentWarning::updateOrCreate(
                        [
                            'job_id' => $job->id,
                            'type'   => 'job' 
                        ],
                        [
                            'deleted' => 0
                        ]
                    );
                }
            }
            else{
                JobSentWarning::updateOrCreate(
                    [
                        'job_id' => $job->id,
                        'type'   => 'job'
                    ],
                    [
                        'deleted' => 1
                    ]
                );               
            }
        }
    }
}