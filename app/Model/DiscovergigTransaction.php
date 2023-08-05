<?php 
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class DiscovergigTransaction extends Model{
	protected $table 		= 'discovergig_transactions';
	protected $primaryKey 	= 'id';
	protected $fillable 	= ['amount', 'type', 'description', 'direction', 'user_id', 'ref_id', 'serial', 'offer_id' , 'status'];
    // type: Service Fee, Fixed Price, Withdrawl Fee, Withdrawal, : Processing Fee, Payment, Hourly, Fixed ..., 
	// status: pending, available
	public static function boot(){
	    parent::boot();
	    self::creating(function($model){ 
	    	$model->serial 	   =  self::GenerateSerial();
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
	public function getMilestoneName(){
		$result = ""; 
		$offer =   $this->getOffer(); 
		if(!isset($offer))
			return ""; 
		$job 	= 	$offer->getJob();
		$client = 	$job->getClient();
		$result = $client->accounts->name . " - " . $job->headline; 
		if($this->ref_id){
			$milestone = 	Milestone::where('id', $this->ref_id)
									->first(); 
			if(isset($milestone))
				$result .=  " - " .  $milestone->headline;
		} 
		return $result;
	}
	public function getClient(){
		$offer =  $this->getOffer();
		if(!isset($offer)) return $offer;	
		return $offer->getClient();		
	}
}