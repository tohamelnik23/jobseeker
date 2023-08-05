<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;
use Auth;
use Carbon\Carbon;
class Job extends Model{
    protected $fillable = ['user_id','job_date','job_time','status', 'serial', 'zip', 'city', 'state', 'address2', 'address1', 'description', 'headline', 'lng', 'lat', 'budget_start', 'budget_end', 'estimted_budget', 'payment_type', 'job_type', 'hire_more', 'close_reason', 'location_type', 'type', 'shift_end_date', 'shift_end_time', 'duration', 'duration_type', 'job_date_time', 'shift_end_date_time'];
    protected $table    = "jobs";
    // 0: draft, 1: published, 2: closed, 3:deleted
    public static function boot(){
        parent::boot();               
        self::creating(function($model){
            $model->serial 	        =   self::GenerateSerial();
            $model->hire_more       =   0;
        });
        self::created(function($model){
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
                $sn .=  $chars[rand(0, $max)];
            $apitoken   =   self::where('serial', $sn)->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;     
    } 
    public function getQuestion(){
        $questions   =   Skillquestion::where('job_id', $this->serial)
                                ->get(); 
        return $questions; 
    } 
    public function getJobDate( $type = "start" ){
        if($type == "start")
            $job_date =  date("m/d/Y", strtotime($this->job_date));
        else
            $job_date =  date("m/d/Y", strtotime($this->job_date));

        return $job_date;
    } 
    public function getJobTime( $type = "start"){
        if($type == "start")
            $job_time =  date("g:i A", strtotime($this->job_time));
        else
            $job_time =  date("g:i A", strtotime($this->shift_end_time)); 
        return $job_time;
    } 
    public function getDisplayDate(){
        $now = Carbon::now();
        if( $now->toDatestring() == $this->job_date ){
            return "Today";
        }
        $now = Carbon::now()->subDay();
        if( $now->toDatestring() == $this->job_date ){
            return "Yesterday";
        }
        $now = Carbon::now()->addDay();
        if( $now->toDatestring() == $this->job_date ){
            return "Tomorrow";
        }
        return $this->getJobDate();
    } 
    public function getSkills($as_array = 0){
        $skills =    Skill::select("skills.*")
                        ->leftJoin('job_skills', 'job_skills.skill_id', 'skills.id')
                        ->where('job_id', $this->id)
                        ->where('job_skills.deleted', 0)   
                        ->get();
        if($as_array == 1){
            $result  = array();
            foreach($skills as $skill){
                $result[] = $skill->id;
            }
            return $result;
        }
        else
            return $skills;
    }
    public function getQuestions(){ 
        $question   =       Question::select("qeuestions.*")
                                ->leftJoin('job_question', 'job_question.question', 'qeuestions.serial')
                                ->where('job_question.job_id', $this->id)
                                ->where('job_question.deleted', 0)   
                                ->get();
        return $question;
    }
    public function getBudget(){
        $budget_string = "Not Defined";
        if($this->estimted_budget){
            $budget     =  DB::table("suggested_budget") 
                                ->where('id', $this->estimted_budget)
                                ->first();
            if(isset($budget)){
                if($budget->end_value)
                    $budget_string =  "$" . number_format($budget->start_value, 2) . " - $" .  number_format($budget->end_value, 2);
                else
                    $budget_string =  "$" . number_format($budget->start_value, 2) . " +";
            } 
        }
        else{
            $budget_string =  "$" . number_format($this->budget_start, 2) . " - $" .  number_format($this->budget_end, 2);
        } 
        return  $budget_string;
    }
    public function getPropoerBudget(){
        $amount = 0;
        if($this->estimted_budget){
            $budget     =   DB::table("suggested_budget") 
                                ->where('id', $this->estimted_budget)
                                ->first();
            if(isset($budget)){
                if($budget->end_value)
                    $amount = $budget->end_value;
                else
                    $amount = $budget->start_value;
            }
        }
        else{
            $amount = $this->budget_end;
        }
        return  $amount;
    } 
    public function getClient() {
        $client =   User::where('id', $this->user_id)
                        ->first();
        return $client;
    }
    public function countActions($type){
        $counts = 0;
        switch($type){
            case 'proposals': 
                $archived_users  =   Archive::where('job_id', $this->id)	
                                        ->where('status', 1)
                                        ->get(); 
                $archived_users_array = array();
                foreach($archived_users as $archived_user){
                    $archived_users_array[] = $archived_user->user_id;
                }
                $counts     =   Proposal::where('status', '<=' , 2)
                                    ->where('job_id', $this->id)
                                    ->whereNotIn('user_id', $archived_users_array)
                                    ->count();
                break;
            case 'messaged':
                $archived_users  =   Archive::where('job_id', $this->id)	
                                        ->where('status', 1)
                                        ->get(); 
                $archived_users_array = array();
                foreach($archived_users as $archived_user){
                    $archived_users_array[] = $archived_user->user_id;
                }

                $declined_users  =   Decline::where('job_id', $this->id)
                                            ->where('decline_type', "<>" ,"invite")
                                            ->get(); 
                
                foreach($declined_users as $archived_user){
                    $archived_users_array[]     =   $archived_user->decline_user;
                } 
                $counts     =   MessageList::where('status',  0)
                                    ->where('job_id', $this->id)
                                    ->whereNotIn('to_user_id', $archived_users_array)
                                    ->count(); 
                break;
            case 'hired':
                $counts     =   Offer::where('status', '<>' , 0)
                                    ->where('status', '<>' , 3)
                                    ->where('job_id', $this->id)
                                    ->count(); 
                break;
            case 'pending_offers':
                $counts     =   Offer::where('status',      0)
                                    ->where('job_id', $this->id)
                                    ->count();
                break;
            case 'offers':
                $counts     =   Offer::where(function ($query){
                                        $query->where('status',      0) 
                                                ->orWhere('status',  3);
                                    })
                                    ->where('job_id', $this->id)
                                    ->count();
                break;
            case 'invited': 
                $counts    =    Invite::where('status', 0)
                                    ->where('job_id', $this->id)
                                    ->count();
                break;
            case 'hires':
                $counts     =   Offer::where('status', '<>' ,0)
                                    ->where('status', '<>' , 3)
                                    ->where('job_id', '<>' , $this->id)
                                    ->where('employer_id', $this->user_id)
                                    ->groupBy("offers.user_id")
                                    ->select("offers.user_id")
                                    ->get();
                $counts     =   count($counts); 
                break;
            case 'archived':
                // archved
                $archived_total     =   Archive::where('archieved.job_id', $this->id)	
                                            ->where('archieved.status', 1)
                                            ->join('proposals', 'proposals.job_id', 'archieved.job_id')
                                            ->where('proposals.status', '<=', 2)
                                            ->count(); 
                //declined
                $declined_total     =   Decline::where('job_id', $this->id)
                                            ->where('decline_type', '<>', 'invite')
                                            ->count(); 
                $counts     =   $archived_total + $declined_total;
                break;
        }
        return $counts;
    }
    public function countSaved(){
        $total_saved =  SavedFreelancer::where('client_id', Auth::user()->id)
                                        ->where('status', 1)
                                        ->count();
        return  $total_saved;
    }
    public function countShortlisted(){
        $archived_users  =   Archive::where('job_id', $this->id)	
                                ->where('status', 1)
                                ->get(); 
        $archived_users_array = array();
        foreach($archived_users as $archived_user){
            $archived_users_array[] = $archived_user->user_id;
        }
        $declined_users  =   Decline::where('job_id', $this->id)
                                    ->where('decline_type', "<>" ,"invite")
                                    ->get(); 
        
        foreach($declined_users as $archived_user){
            $archived_users_array[]     =   $archived_user->decline_user;
        }
        $total_saved    =   Shortlist::where('job_id', $this->id)
                                        ->where('status', 1)
                                        ->whereNotIn('user_id', $archived_users_array)
                                        ->count();
        return  $total_saved;
    }
    public function checkApplied($user_id){  
        $is_applied =       Proposal::where('job_id', $this->id)
                                    ->where('user_id', $user_id)
                                    ->count(); 
        return $is_applied;
    } 
    public function checkInvited($user_id, $status = 0){
        $is_invited =   Invite::where('job_id', $this->id)
                            ->where('user_id', $user_id)
                            ->where('status', $status)
                            ->count(); 
        return $is_invited;
    } 
    public function getProposals($view = 0){
        $proposals   =   Proposal::where('job_id', $this->id) 
                                ->get(); 
        if($view == 0) return $proposals; 
        $proposal_users_array = array();
        foreach($proposals as $proposal){
            $proposal_users_array[] = $proposal->user_id;
        }
        return $proposal_users_array;
    } 
    public function getProposal($user_id){  
        $proposal   =   Proposal::where('job_id', $this->id)
                                ->where('user_id', $user_id)
                                ->first(); 
        return $proposal;
    } 
    public function getInvite($user_id, $status = 0){
        $invite 	=  	Invite::where('user_id', $user_id)
                                ->where('job_id', $this->id)
                                ->where('status', $status)
                                ->first();
		return $invite;
    } 
    public function getOffer($user_id){  
        $proposal   =   Offer::where('job_id', $this->id)
                                ->where('user_id', $user_id)
                                ->first(); 
        return $proposal;
    }
    public function SavedJob($user_id){
        $is_applied =   SavedJob::where('job_id', $this->id)
                            ->where('user_id', $user_id)
                            ->where('status', 1)
                            ->count();
        return $is_applied;
    } 
    public function getDecline($user_id){
        $decline    =   Decline::where('declines.job_id', $this->id)
                            ->where('declines.decline_user', $user_id)
                            ->leftJoin('decline_reasons', 'decline_reasons.id', 'declines.decline_reason')
                            ->select("declines.*", "decline_reasons.content")
                            ->first();
        return $decline;    
    } 
    public function proposeEnable($user_id){
        $decline = Decline::where('job_id', $this->id)
                        ->where('user_id', $user_id)
                        ->first();
        if(isset($decline)) return 0; 
        return 1;
    } 
    public function getSuggestBudget(){
        if($this->estimted_budget){ 
            $suggested_budget =  DB::table("suggested_budget")->where('id', $this->estimted_budget)->first();
            if(!isset($suggested_budget))
                return 0;
            if($suggested_budget->end_value  == 0){
                return $suggested_budget->start_value;
            }
            else{
                return $suggested_budget->end_value;
            }
        }
        else{
            if($this->budget_start !== $this->budget_end){
                return $this->budget_end;
            }
            else{
                return $this->budget_start;
            }
        }
    } 
    public function getMessageList($user_id, $status = 0){ 
        $messageList    =   MessageList::where('job_id', $this->id)
                                ->where('to_user_id', $user_id)
                                ->where('status', $status)
                                ->where('type', 'job')
                                ->first();
        return $messageList;
    } 
    public function getCategory(){
        $industry =     Industry::where('id', $this->job_type)
                            ->first(); 
        return $industry;
    }
    public function CloseAction(){
        // remove all invites
        $invites    =   Invite::where('job_id', $this->id)
                            ->where('status', 0)
                            ->get();
        foreach($invites as $invite){

        }

        // remove all proposal
        $proposal_status =  array(0, 1, 2, 10);
        $proposals      =   Proposal::whereIn('status', $proposal_status)
                                ->where('job_id', $this->id)
                                ->get();
        
        foreach($proposals as $proposal){

        }

        // remove all offer
      //  $offers     =      Offer::where('status', )


    } 
    public function getJobCategory(){
        $subcategory    =   SubCategory::where('id', $this->job_type)
                                ->first();
        if(!isset($subcategory)) return null;

        $industry   =   Industry::where('id', $subcategory->category_id)
                                ->first();
        return  $industry;
    } 
    public function getJobType(){
        $subcategory    =   SubCategory::where('id', $this->job_type)
                                ->first(); 
        return  $subcategory;
    } 
    public function closeJob(){
        //get all proposals
        $proposals   =   Proposal::where('job_id', $this->id)
                            ->where('status', '<', 3)
                            ->get(); 
        foreach($proposals as $proposal){
            $notification_message 	= 	"Your job for '" . $this->headline ."' has been closed or has expired";
            $notification_ref 		=	$proposal->serial;
            //Notification
            Notification::create([
                'notifications_fromuser' 	=>   0,
                'notifications_touser'		=>   $proposal->user_id,
                'notifications_value'		=>   $notification_message,
                'notification_ref'          =>   $notification_ref,
                'notifications_type'		=>   'job_closed'
            ]);
            $proposal->update([
                'status' => 5
            ]);
        } 
        //close the job 
        $this->status = 2;
        $this->save();
    }
    public function get_attachments(){
        $attachments =  Attachment::where('type', 'job')
                            ->where('ref_id', $this->id)
                            ->where('deleted', 0)
                            ->get();
        return $attachments;
	}
}