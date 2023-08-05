<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use DB;
class Decline extends Model{
    protected $fillable = ['job_id','decline_reason','user_id', 'decline_user', 'decline_type', 'decline_note', 'other_reason', 'decline_reference'];
    protected $table    = "declines";
    public static function boot(){
        parent::boot(); 
        self::creating(function($model){
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
    public static function getProposals($user_id, $type){
        $declines   =   Decline::where('user_id', $user_id)
                            ->where('decline_type', $type)
                            ->get();
        return $declines;
    }  
    public function getJob(){ 
        $job =  Job::where('id', $this->job_id)
                    ->first();
        return $job;
    } 
    public function getReference(){ 
        if($this->decline_type == "proposal"){
            $proposal  =    Proposal::where('serial', $this->decline_reference)
                                    ->first();
            return $proposal;
        }

        if($this->decline_type == "invite"){
            $invite  =    Invite::where('serial', $this->decline_reference)
                                    ->first();
            return $invite;
        } 
        return null;
    } 
    public function getDeclineMessage($request_side = "freelancer" , $reason = 'full'){
        $result = ""; 

        if($this->decline_type == "proposal"){
            if($request_side == "freelancer"){
                if($this->user_id == $this->decline_user) // self decline
                        $result = "Withdrawn";
                    else
                        $result = "Declined by Client";    
            } 
            if($request_side == "client"){
                if($this->user_id == $this->decline_user) // self decline
                    $result = "Withdrawn by Freelancer";
                else
                    $result = "Declined";    
            }     
        }
        
        if( ($this->decline_type == "offer") ||  ($this->decline_type == "invite") ){
        
            if($request_side == "freelancer"){
                if($this->user_id == $this->decline_user) // self decline
                    $result = "Declined";
                else
                    $result = "Withdrawn by Freelancer";    
            } 
            if($request_side == "client"){
                if($this->user_id == $this->decline_user) // self decline
                    $result = "Declined by Freelancer";
                else
                    $result = "Withdrawn";    
            }  
        } 
         
        if($reason == 'full'){
            $reason = DB::table("decline_reasons")->where('id', $this->decline_reason)->first();  
            if(isset($reason)){ 
                if($result != "")
                    $result .= ': ';
                $result .= $reason->content;
            }
        }
        return $result;
    }
}