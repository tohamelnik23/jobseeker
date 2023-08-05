<?php 
namespace App\Console\Commands; 
use Illuminate\Console\Command;
use Config, Mail, DB;
use Carbon\Carbon;  
use App\Model\Sendmail;
use App\Model\Job;
use App\User;
use App\Model\Invite;
use App\Model\Proposal;
class InterestingJobs extends Command{
    protected $description 	= 'This is for pending to available.';
    protected $signature 	= 'jobs:interesting'; 
    public function __construct(){
        parent::__construct();
    }
    public function handle(){
        $freelancers    =   User::where('role', 1)
                                ->where('phone_verified_status', 1)
                                ->where('availability', 'active')
                                ->get();
        $now =  Carbon::now()->subDays(1);
        foreach($freelancers as $freelancer){
            $setting_value =  $freelancer->getValue("notification_email_discovergigs_notification"); 
            if($setting_value == "yes"){
                $all_skills     = $freelancer->getAllSkills();
                $skills_array   = array();
                foreach($all_skills as $all_skill){
                    $skills_array[] = $all_skill->skill;
                } 
                $job_ids    =   array();
                $invites    =   Invite::where('user_id', $freelancer->id)
                                    ->where('created_at',  '>=', $now->toDateTimeString())
                                    ->select("job_id")
                                    ->get();
                foreach($invites as $invite){
                    $job_ids[]  =   $invite->job_id;
                }
                $proposals  =   Proposal::where('user_id', $freelancer->id)
                                    ->where('created_at',  '>=', $now->toDateTimeString())
                                    ->select("job_id")
                                    ->get();
                foreach($proposals as $proposal){
                    $job_ids[]  =   $proposal->job_id;
                }
                $jobs   =   Job::where('jobs.status', 1)
                                ->where('jobs.created_at',  '>=', $now->toDateTimeString()) 
                                ->whereNotIn('id', $job_ids)
                                ->join("job_skills", "job_skills.job_id", "jobs.id")
                                ->whereIn("job_skills.skill_id", $skills_array)
                                ->groupBy('jobs.id')
                                ->select("jobs.*")
                                ->take(10)
                                ->get(); 
                if(count($jobs)){
                    $sendmail	= 	new Sendmail;
                    $maildata	= 	array(
                        'template_name'	=>	'emails.search_jobs',
                        'to_name'		=>	$freelancer->accounts->firstname,
                        'to_email'		=>	$freelancer->email,
                        'subject'		=>	'Interesting Jobs',
                        'jobs'          =>  $jobs
                    );
                    $sendmail->sendEmail($maildata);
                }     
            }
        }
    }
}