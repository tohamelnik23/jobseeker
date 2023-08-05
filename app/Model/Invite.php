<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Invite extends Model{
    protected $fillable = ['user_id','job_id','status', 'notes', 'serial'];
    protected $table    = "invites"; 
    //0 active, 1: accept, 2: decline 3: archived
    public static function boot(){
        parent::boot(); 
        self::creating(function($model){
            $model->serial 	    =   self::GenerateSerial();
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
    public static function GenerateSerial($digit = 15){
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
    public static function getInvites($user_id, $status = 0){
        $invites =  Invite::where('invites.user_id', $user_id)
                        ->where('invites.status',  $status)
                        ->leftJoin('jobs', 'jobs.id', 'invites.job_id')
                        ->select('jobs.headline', "invites.*", "jobs.serial as job_serial")
                        ->get();
        return $invites;
    } 
    public function getJob(){
        $job    =   Job::where('id', $this->job_id)
                        ->first(); 
        return $job;
    }
    public function getFreelancer(){
        $user   =   User::where('id', $this->user_id)
                        ->first();
        return $user;
    }
}
