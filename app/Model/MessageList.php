<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
use App\User;
class MessageList extends Model{
    protected $primaryKey 	= 'id';
	protected $table 		= 'message_list'; 
    protected $fillable 	= ['user_id', 'to_user_id', 'status', 'job_id', 'serial', 'type'];
    // status: 0 - active, 1 - paused, 2 - blocked
    // type: job, offer, direct
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
                $sn .= $chars[rand(0, $max)];
            $apitoken = self::where('serial', $sn)->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;
    }
    public static function addMessageList($user_id, $to_user_id, $job_id, $type = "job"){
        $message_list   = MessageList::updateOrCreate([
                                'user_id'       => $user_id,
                                'to_user_id'    => $to_user_id,
                                'type'          => $type,
                                'job_id'        => $job_id
                            ],[
                                'status'        => 0
                            ]);
        return $message_list;
    }
    public function getToUser($user_id){
        if($user_id == $this->to_user_id)
            $to_user_id = $this->user_id;
        else
            $to_user_id = $this->to_user_id; 
        $client =  User::where('id', $to_user_id)
                    ->first();
        return $client;
    }
    public function getJob(){ 
        $job        =  null;
        if($this->type == "job"){
            $job    =   Job::where('id', $this->job_id)
                            ->first();    
        }

        if($this->type == "offer"){
            $job    =   Offer::where('id', $this->job_id)
                            ->first();
        }
        return $job;
    }

    public function getLastMessage($user_id = -1){
        if($user_id == -1)
            $message =  Message::where('message_list', $this->id)
                            ->orderBy("id", 'desc')
                            ->first();
        else
            $message =  Message::where('message_list', $this->id)
                            ->orderBy("id", 'desc')
                            ->where('from_user', $user_id)
                            ->first(); 
        return $message;
    }
    public function  caculateUnreadMessage($to_user_id){
        $unread_messages     =  Message::where('message_list', $this->id)
                                    ->where('to_user', $to_user_id)
                                    ->where('message_read', 0)
                                    ->count();
        return $unread_messages;
    } 
}