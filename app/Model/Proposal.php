<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
use App\Model\Role;
use App\User;
class Proposal extends Model{
    protected $primaryKey 	= 'id';
	protected $table 		= 'proposals'; 
    protected $fillable 	= ['coverletter', 'proposal_amount','job_id', 'user_id', 'status', 'serial', 'role'];
    // 0: active, 1: interview request,  2: interview  3. declined, 4: archive 5: expired,  10: pending offer 20: hired
    public static function boot(){
        parent::boot();         
        self::creating(function($model){
            $model->status      =   0;
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
    public function getJob(){
        $job    =   Job::where('id', $this->job_id)
                        ->first(); 
        return $job;
    }
    public function getAnswers(){ 
        $answers    =  ProposalAnswer::where('proposal', $this->id)
                            ->where('proposal_answer.deleted', 0)
                            ->leftJoin('qeuestions', 'qeuestions.serial', 'proposal_answer.question')
                            ->select('qeuestions.*', 'proposal_answer.answer')
                            ->get();
        return $answers;
    }
    public function getProfile(){
        $role =     Role::where('id', $this->role)
                        ->first();
        return $role;
    }
    //$type: active, submitted, archieved
    public static function getProposals($user_id, $type){
        switch($type){
            case 'active':
                $proposals =    self::where(function ($query){
                                        $query->where('status',      1) 
                                                ->orWhere('status',    2);
                                    })
                                    ->where('user_id', $user_id)
                                    ->get();
                break;
            case 'submitted':
                $proposals  =   self::where('status', 0)
                                    ->where('user_id', $user_id)
                                    ->get();
                break;
            case 'archieved':
                $proposals  =   self::where('status', '>', 1)
                                    ->where('status', '<', 10)
                                    ->where('user_id', $user_id)
                                    ->get();
                break;
        } 
        return $proposals; 
    }

    public function getFreelancer(){
        $freelancer =   User::where('id', $this->user_id)
                            ->first();
        return $freelancer;
    }
}