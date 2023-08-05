<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class Milestone extends Model{
    protected $fillable = ['offer_id','amount','deposit_status', 'start_date', 'end_date', 'milestone_sort', 'deleted','headline' ,'serial', 'status', 'paid_amount'];
    protected $table    = "milestones";
    //completed, inactive, active, cancelled, 
    public static function boot(){
        parent::boot(); 
        self::creating(function($model){
            $model->paid_amount     =   0;
            $model->deleted         =   0;
            // $model->serial 	        =   self::GenerateSerial(); 
            $latest_priority    =   self::where('milestone_sort', $model->category_company)
                                        ->where('deleted', 0)
                                        ->orderBy('milestone_sort', 'desc')
                                        ->where('offer_id', $model->offer_id)
                                        ->first();
            if(isset($latest_priority)){
                $model->milestone_sort  =   $latest_priority->milestone_sort + 1;
            }
            else{
                $model->milestone_sort =    0;
            }
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
    public function checkSubmittionRequest(){
        $submit_work    =   SubmitWork::where('milestone_id', $this->id)
                                    ->where('status', 'active')
                                    ->first();
        return $submit_work;
    }
    public function getSubmissions(){
        $submit_works   =   SubmitWork::where('milestone_id', $this->id) 
                                    ->get();
        return  $submit_works;
    } 
    public function getOffer(){
        $offer  =   Offer::where('id',  $this->offer_id)
                        ->first();
        return $offer;
    }
}