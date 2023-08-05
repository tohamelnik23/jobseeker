<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class Message extends Model{
    protected $primaryKey 	= 'id';
	protected $table 		= 'messages'; 
    protected $fillable 	= ['from_user', 'to_user' , 'serial', 'message_sendtime' ,'message_list', 'message_type', 'message_read', 'message_content', 'ref_id']; 
    public static function boot(){
        parent::boot();
        self::creating(function($model){ 
            $model->serial 	        =   self::GenerateSerial();
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
                $sn .= $chars[rand(0, $max)];
            $apitoken = self::where('serial', $sn)->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;                 
    }

    //message_type: 0 - normal, 1: proposal, 2:invite, 3: offer, 4: decline
    public static function addMessage($from_user, $to_user, $message_list , $content, $type = 0, $time = null, $ref_id = null){
        if($time){
        }
        else{
            $time = date('Y-m-d H:i:s');
        } 

        if($type == 1){
            $message_read = 1;
        } 
        else
            $message_read = 0;        
        $message    =   Message::create([
                            'from_user'          => $from_user,
                            'to_user'            => $to_user,
                            'message_list'       => $message_list,
                            'message_content'    => $content,
                            'message_type'       => $type,
                            'message_read'       => $message_read,
                            'ref_id'             => $ref_id,
                            'message_sendtime'   => $time
                        ]); 
        return $message;
    }

    public function generateURL($user_id){ 
        $link   =   "#";
        switch($this->message_type){
            case 1: // Proposal
                $proposal = Proposal::where('id', $this->ref_id)->first();
                if(isset($proposal)){
                    if($user_id == $proposal->user_id){ // user_id
                        $link  =  route('jobs_proposal_details', $proposal->serial);
                    }
                    else{
                        $job        =   $proposal->getJob();
                        $freelancer =   $proposal->getFreelancer();
                        $link       =   route('employer.jobs.mainaction.user', [$job->serial, $freelancer->serial]);
                    }
                }
                break;
            case 3: // offer
                $offer  =   Offer::where('id', $this->ref_id)
                                ->first(); 
                if(isset($offer)){
                    if($user_id == $offer->user_id){ // user_id
                        $link  =  route('jobs_offer_details', $offer->serial);
                    }
                    else{
                        $link  =  route('employer.jobs.viewoffer', $offer->serial);
                    }
                }
                break;
            case 4: // offer
                $offer  =   Offer::where('id', $this->ref_id)
                                ->first(); 
                if(isset($offer)){
                    if($user_id == $offer->user_id){ // user_id
                        $link  =  route('jobs_contract_details', $offer->serial);
                    }
                    else{
                        $link  =  route('employer.contract_details', $offer->serial);
                    }
                }
                break;
        }
        return $link;
    }

    public function getDetailLabel(){
        $result = "";
        switch($this->message_type){
            case 1: // Proposal
                $result = "View Details";
                break;
            case 3: // offer
                $result = "View Offer";
                break;
            case 4: // offer
                $result = "View Contract";
                break;
        }
        return $result;
    }
}