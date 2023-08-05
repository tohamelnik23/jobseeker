<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class Escrow extends Model{
    // direction. 0: in, 1: out
    protected $fillable = ['amount','ref_id','user_id','status', 'offer_id', 'type', 'description', 'direction', 'serial'];
    protected $table    = "escrow";
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
    public function getOffer(){
		$offer 	= 	Offer::where('id', $this->offer_id)
						->first();
		return $offer;
	}
    public function getFreelancer(){
		$offer =  $this->getOffer();
		if(!isset($offer)) return $offer;	
		return $offer->getFreelancer();		
	}
    


}