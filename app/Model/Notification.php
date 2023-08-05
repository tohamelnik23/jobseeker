<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
use App\User; 
use Mail;
use App\Helpers\Mainhelper;
class Notification extends Model{
    protected $primaryKey 	= 'notifications_id';
	protected $table 		= 'notifications'; 
    protected $fillable 	= ['notifications_fromuser', 'notifications_touser', 'notifications_value', 'notifications_readby', 'notifications_sent_email', 'notifications_sent_sms' ,'notifications_serial', 'notifications_type', 'notification_ref', 'notifications_deleted'];    
    public static function boot(){
        parent::boot(); 
        self::creating(function($model){
            $model->notifications_readby     	=   0;
            $model->notifications_deleted     	=   0;
            $model->notifications_sent_email    =   0;
			$model->notifications_sent_sms      =   0;
            $model->notifications_serial 	    =   self::GenerateSerial(); 
        });

        self::created(function($model){ 
            $model->sendNotifiaction(); 
        });

        self::updating(function($model){
        });

        self::updated(function($model){
        });

        self::deleting(function($model){
        });

        self::deleted(function($model){
        });

    }
    public static function GenerateSerial($digit = 20){
        $chars 	= array(0,1,2,3,4,5,6,7,8,9);
        $max 	= count($chars) - 1; 
        while(1){
            $sn = '';
            for($i = 0; $i < $digit; $i++)
                $sn .= $chars[rand(0, $max)];
            $apitoken = self::where('notifications_serial', $sn)->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;                 
    }
    public function generateURL(){
        if($this->notifications_touser == 0){
            $user = User::where('id', $this->notifications_fromuser)
                        ->first();   
            switch($this->notifications_type){
                case 'address_verification': 
                    if($user->role == '1')
                        $url =  route('master.members.show', $user->id); 
                    if($user->role == '2')
                        $url =  route('master.members.employer.show', $user->id); 
                    break; 
                case 'profile_verification':
                    if($user->role == '1')
                        $url =  route('master.members.show', $user->id); 
                    if($user->role == '2')
                        $url =  route('master.members.employer.show', $user->id); 
                    break;
                case 'driverlicense_verification':
                    if($user->role == '1')
                        $url =  route('master.members.show', $user->id); 
                    if($user->role == '2')
                        $url =  route('master.members.employer.show', $user->id); 
                    break;    
                case 'bank_verification':
                    if($user->role == '1')
                        $url =  route('master.members.show', $user->id); 
                    if($user->role == '2')
                        $url =  route('master.members.employer.show', $user->id); 
                    break;
                case 'loan_request':
                case 'loan_change':
                    if($user->role == '1')
                        $url =  route('master.members.loans', $user->id);  
                    break;
            } 
        }
        else{
            $user = User::where('id', $this->notifications_touser)
                            ->first(); 
            switch($this->notifications_type){
                case 'address_verification': 
                    if($user->role == '1')
                        $url =  route('employee.profile'); 
                    if($user->role == '2')
                        $url =  route('employer.profile'); 
                    break; 
                case 'profile_verification':
                    if($user->role == '1')
                        $url =  route('employee.profile'); 
                    if($user->role == '2')
                        $url =  route('employer.profile'); 
                    break; 
                case 'bonus_sent':
                    $url = route('employee.reports.earnings_history');
                    break;
                case 'escrow_requested':
                    $url = route('employer.reports.billing_history');
                    break;
                case 'job_posted':
                case 'job_modified':
                    $url =  route('employer.jobs.mainaction', [$this->notification_ref, 'suggested']);
                    break;
                case 'proposal_applied':
                    $url =  route('employer.jobs.mainaction', [$this->notification_ref, 'applicants']);
                    break;
                case 'invited_accepted':
                    $freelancer =   $this->getFromUser();
                    $url        =   route('employer.jobs.mainaction.user', [$this->notification_ref, $freelancer->serial]);
                    break;
                case 'invtiation_sent':
                case 'invite_decline_client_sent':
                    $url        =   route('jobs_invites_details', $this->notification_ref);
                    break;
                case 'offer_sent':
                case 'offer_decline_client_sent':
                    $url        =   route('jobs_offer_details', $this->notification_ref);
                    break;
                case 'proposal_decline_client_sent':
                    $url        =   route('jobs_proposal_details', $this->notification_ref);
                    break;
                case 'offer_accepted':
                    $url        =  route('employer.contract_details', $this->notification_ref);
                    break;
                case 'offer_started': 
                    $url        =  route('jobs_contract_details', $this->notification_ref);
                    break;
                case 'job_closed':
                    $url        =  route('jobs_proposal_details', $this->notification_ref);
                    break;
                case 'gig_expiring':
                    $url        =   route('employer.jobs.mainaction', [$this->notification_ref, 'job-details']);
                    break;
                case 'gig_expired':
                    $url        =   route('employer.jobs.mainaction', [$this->notification_ref, 'job-details']);
                    break;
                case 'offer_expired':
                    $url        =   route('employer.contract_details', $this->notification_ref);
                    break;
                case 'withdrawn_user':
                case 'contract_sent':
                case 'payment_request': 
                    $url    =    $this->notification_ref;
                    break;
            }
        }
        return $url;
    }
    public function generateLabel(){
        $result = "View Details";
        switch($this->notifications_type){
            case 'proposal_applied':
                $result  = "View Proposal";
                break; 
            case 'invtiation_sent':
                $result =   "Respond";
                break; 
            case 'invited_accepted':
            case 'withdrawn_user':
                $result =   "View Gig";
            case 'offer_started':
            case 'offer_accepted':
                $result =   "View Contract";
                break;
            case 'bonus_sent':
            case 'escrow_requested':
                $result =   "View Payment";
                break;
            case 'contract_sent':
                $result = "View Details";
                break;
            case 'payment_request':
                $result = "View Request";
                break;
            case 'offer_sent':
                $result = "View Offer";
                break;
            case 'gig_expiring':
            case 'gig_expired':
                $result = "View Gig";
                break;
            case 'job_modified': 
            case 'job_posted':
                $result = "Search Freelancer";
                break;
            case 'offer_expired':
                $result = "View Offer";
                break;
            case 'invite_decline_client_sent':
            case 'proposal_decline_client_sent':
            case 'offer_decline_client_sent':
                $result = "View Gig";
                break;
        }
        return $result;
    }                                                                           
    public function sendNotifiaction(){
        if($this->notifications_touser != 0){
            $user   =   User::where('id', $this->notifications_touser)
                            ->first();
            if(isset($user)){
                switch($this->notifications_type){
                    case 'job_posted':
                    case 'job_modified':
                        $setting_value =  $user->getValue("notification_email_job_posted_modified");
                        if($setting_value == "yes"){
                            $job        =   Job::where('serial', $this->notification_ref)
                                                ->first();
                            if(isset($job)){
                                $sendmail	 = 	new Sendmail; 
                                if($this->notifications_type == "job_posted")
                                    $subject = "Job is Posted";
                                else
                                    $subject = "Job is Modified"; 
                                $maildata	= 	array(
                                    'template_name'	=>	"emails.". $this->notifications_type,
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'job'		    =>	$job
                                );
                                $sendmail->sendEmail($maildata);
                            }
                        }
                        break;
                    case 'proposal_applied':
                        $setting_value =  $user->getValue("notification_email_proposal_received");
                        if($setting_value == "yes"){
                            $job        =   Job::where('serial', $this->notification_ref)
                                                ->first();
                            $freelancer =   $this->getFromUser();
                            if(isset($job) && isset($freelancer)){
                                $proposal    =   $job->getProposal($freelancer->id);
                                if(isset($proposal)){ 
                                    $sendmail	 = 	 new Sendmail;
                                    $subject     =   "Proposal  Applied"; 
                                    $maildata	 = 	array(
                                        'template_name'	=>	"emails.proposal_received",
                                        'to_name'		=>	$user->accounts->firstname,
                                        'to_email'		=>  $user->email, 
                                        'subject'		=>	$subject,
                                        'freelancer'    =>  $freelancer,
                                        'proposal'      =>  $proposal,
                                        'job'		    =>	$job
                                    );
                                    $sendmail->sendEmail($maildata);    
                                }
                            }
                        }
                        break;
                    case 'invited_accepted':
                        $setting_value      =  $user->getValue("notification_email_interview_accepted");
                        if($setting_value == "yes"){
                            $job        =   Job::where('serial', $this->notification_ref)
                                                ->first();
                            $freelancer =   $this->getFromUser();
                            if(isset($job) && isset($freelancer)){
                                $sendmail	 = 	 new Sendmail;
                                $subject     =   "Invitation  Accepted"; 
                                $maildata	 = 	 array(
                                    'template_name'	=>	"emails.invitation_accepted",
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'freelancer'    =>  $freelancer,
                                    'job'		    =>	$job
                                );
                                $sendmail->sendEmail($maildata);
                            }
                        } 
                        break;
                    case 'invtiation_sent':
                        $setting_value =  $user->getValue("notification_email_offer_received");
                        if($setting_value == "yes"){
                            $invitation     =   Invite::where('serial', $this->notification_ref)
                                                    ->first();
                            if(isset($invitation)){
                                $job = $invitation->getJob();
                                if(isset($job)){
                                    $sendmail	 = 	 new Sendmail;
                                    $subject     =   "Invitation  Received"; 
                                    $maildata	 = 	 array(
                                        'template_name'	=>	"emails.invitation_received",
                                        'to_name'		=>	$user->accounts->firstname,
                                        'to_email'		=>  $user->email,
                                        'subject'		=>	$subject, 
                                        'invitation'    =>  $invitation,
                                        'job'		    =>	$job
                                    );
                                    $sendmail->sendEmail($maildata);
                                }
                            }
                        } 
                        break;
                    case 'offer_sent':
                        $setting_value =  $user->getValue("notification_sms_offer_received");
                        if($setting_value == "yes"){
                            $offer  =   Offer::where('serial', $this->notification_ref)
                                            ->first();
                            if(isset($offer)){
                                $sendmail	 = 	 new Sendmail;
                                $subject     =   "Offer  Received"; 
                                $maildata	 = 	 array(
                                    'template_name'	=>	"emails.offer_received",
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject, 
                                    'offer'         =>  $offer
                                );
                                $sendmail->sendEmail($maildata);
                            }
                        }
                        break;
                    case 'invite_decline_client_sent':
                        $setting_value =  $user->getValue("notification_email_proposal_rejected");
                        if($setting_value == "yes"){
                            $invite     =   Invite::where('serial', $this->notification_ref)
                                                ->first();
                            if(isset($invite)){
                                $sendmail	 = 	 new Sendmail;
                                $subject     =   "Discover Gig Notification";
                                $job         =   $invite->getJob(); 
                                $maildata	 = 	 array(
                                    'template_name'	=>	"emails.client_decline",
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'type'          =>  'invite',
                                    'client'        =>  $this->getFromUser(),
                                    'freelancer'    =>  $user,
                                    'job'           =>  $job
                                );
                                $sendmail->sendEmail($maildata);
                            }
                        }
                        break;
                    case 'proposal_decline_client_sent':
                        $setting_value =  $user->getValue("notification_email_interview_declined");
                        if($setting_value == "yes"){
                            $proposal   =   Proposal::where('serial', $this->notification_ref)
                                                ->first();
                            if(isset($proposal)){
                                $sendmail	 = 	 new Sendmail;
                                $subject     =   "Discover Gig Notification";
                                $job         =   $proposal->getJob(); 
                                $maildata	 = 	 array(
                                    'template_name'	=>	"emails.client_decline",
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'type'          =>  'proposal',
                                    'client'        =>  $this->getFromUser(),
                                    'freelancer'    =>  $user,
                                    'job'           =>  $job
                                );
                                $sendmail->sendEmail($maildata);
                            }
                        }
                        break;
                    case 'offer_decline_client_sent':
                        $setting_value =  $user->getValue("notification_email_proposal_rejected"); 
                        if($setting_value == "yes"){
                            $offer  =   Offer::where('serial', $this->notification_ref)
                                            ->first();
                            if(isset($offer)){
                                $sendmail	 = 	 new Sendmail;
                                $subject     =   "Discover Gig Notification";
                                $job         =   $offer->getJob(); 
                                $maildata	 = 	 array(
                                    'template_name'	=>	"emails.client_decline",
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'type'          =>  'offer',
                                    'client'        =>  $this->getFromUser(),
                                    'freelancer'    =>  $user,
                                    'job'           =>  $job
                                );
                                $sendmail->sendEmail($maildata);
                            }
                        }
                        break;
                    case 'offer_accepted':
                        $setting_value =  $user->getValue("notification_sms_offer_accepted"); 
                        if($setting_value == "yes"){
                            $offer  =   Offer::where('serial', $this->notification_ref)
                                            ->first();
                            if(isset($offer)){
                                $sendmail	 = 	 new Sendmail;
                                $subject     =   "Discover Gig Notification"; 
                                $maildata	 = 	 array(
                                    'template_name'	=>	"emails.offer_accepted",
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'offer'          => $offer,
                                    'freelancer'    =>  $this->getFromUser(),
                                    'client'        =>  $user
                                );
                                $sendmail->sendEmail($maildata);  
                            }
                        }
                        break;
                    case 'offer_started':
                        $setting_value =  $user->getValue("notification_sms_contract_begins");
                        if($setting_value == "yes"){
                            $offer  =   Offer::where('serial', $this->notification_ref)
                                            ->first();
                            if(isset($offer)){
                                $sendmail	 = 	 new Sendmail;
                                $subject     =   "Discover Gig Notification"; 
                                $maildata	 = 	 array(
                                    'template_name'	=>	"emails.contract_started",
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'offer'         =>  $offer, 
                                    'freelancer'    =>  $user
                                );
                                $sendmail->sendEmail($maildata);
                            }
                        }
                        break;
                    case 'job_closed':
                        $setting_value =  $user->getValue("notification_email_job_closed");
                        if($setting_value == "yes"){
                            $proposal   =   Proposal::where('serial', $this->notification_ref)
                                                ->first();
                            if(isset($proposal)){ 
                                $sendmail	 = 	 new Sendmail;
                                $subject     =   "Discover Gig Notification";
                                $job         =   $proposal->getJob(); 
                                $maildata	 = 	 array(
                                    'template_name'	=>	"emails.job_closed",
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'proposal'      =>  $proposal,
                                    'freelancer'    =>  $user,
                                    'job'           =>  $job
                                );
                                $sendmail->sendEmail($maildata); 
                            }
                        }
                        break;
                    case 'gig_expiring':
                        $setting_value =  $user->getValue("notification_email_job_expire_soon");
                        if($setting_value == "yes"){
                            $job        =   Job::where('serial', $this->notification_ref)
                                                ->first();
                            if(isset($job)){
                                $sendmail	= 	new Sendmail;  
                                $subject    =   "Discover Gig Notification"; 
                                $maildata	= 	array(
                                    'template_name'	=>	"emails.job_expiring",
                                    'expire_date'   =>  MasterSetting::getValue('expire_date'),
                                    'expire_warning_date'   =>  MasterSetting::getValue('expire_warning_date'),
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'job'		    =>	$job
                                );
                                $sendmail->sendEmail($maildata);
                            }
                        }
                        break;
                    case 'gig_expired':
                        $setting_value =  $user->getValue("notification_email_job_expired");
                        if($setting_value == "yes"){
                            $job        =   Job::where('serial', $this->notification_ref)
                                                ->first();
                            if(isset($job)){
                                $sendmail	= 	new Sendmail;  
                                $subject    =   "Discover Gig Notification"; 
                                $maildata	= 	array(
                                    'template_name'	=>	"emails.job_expired", 
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'job'		    =>	$job
                                );
                                $sendmail->sendEmail($maildata);
                            }
                        }
                        break;
                    case 'offer_expired':
                        $setting_value =  $user->getValue("notification_email_offer_expired");
                        if($setting_value == "yes"){
                            $offer      =   Offer::where('serial', $this->notification_ref)
                                                ->first();
                            if(isset($offer)){
                                $sendmail	= 	new Sendmail;  
                                $subject    =   "Discover Gig Notification"; 
                                $maildata	= 	array(
                                    'template_name'	=>	"emails.offer_expired", 
                                    'to_name'		=>	$user->accounts->firstname,
                                    'to_email'		=>  $user->email,
                                    'subject'		=>	$subject,
                                    'offer'		    =>	$offer
                                );
                                $sendmail->sendEmail($maildata);
                            }
                        }
                        break;
                }
            }
            /*
            if(isset($user) && ($user->getValue('notification_email') == $send_type) && ( $this->notifications_sent_email == 0 )){
                $attach_data                    =  array();
                $attach_data['notification']    =  $this;
                $attach_data['user']            =  $user; 
                $notification                   =  $this;  
                Mail::send(['html'=>'emails.notification_email'], $attach_data, function($message) use ($notification, $user){
                    $message->to('shinegoldmaster@hotmail.com', $user->accounts->name)->subject("DiscoverGigs.com Help Center");
                    $message->from("support@discovergigs.com",  "The DiscoverGigs.com");
                }); 
                Notification::where('notifications_serial', $this->notifications_serial)
                        ->update([
                            'notifications_sent_email'    => 1
                        ]);
            } 
            if(isset($user) && ($user->getValue('notification_sms') == $send_type) && ($this->notifications_sent_sms == 0 )){ 
                Mainhelper::sendSMSbyTwilio( $user->cphonenumber, $this->notifications_value);
                Notification::where('notifications_serial', $this->notifications_serial)
                    ->update([
                        'notifications_sent_sms'    => 1
                    ]);
            } 
            */
        }
    }
    public function getFromUser(){
        $from_user  =  User::where('id', $this->notifications_fromuser)
                        ->first();
        return $from_user;
    }
    public function setReadBy(){
        $this->notifications_readby = 1;
        $this->save();
    }
}