<?php 
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Transaction extends Model{
	protected $table 		= 'transactions';
	protected $primaryKey 	= 'id';
	protected $fillable 	= ['amount', 'status', 'serial', 'transaction', 'authCode', 'avsResultCode', 'cvvResultCode' , 'accountType', 'accountNumber', 'type', 'user_id', 'card_id'];
    // type: refund, charge, 'void'
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
}