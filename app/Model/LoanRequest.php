<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class LoanRequest extends Model{
    protected $fillable = ['amount','user_id','agreed_amount', 'status', 'serial', 'total_paid', 'offer_id'];
    protected $table    = "loan_requests";
    //pending, paid, rejected, 
    public static function boot(){
        parent::boot();
        self::creating(function($model){
            $model->serial 	        =   self::GenerateSerial();
            $model->total_paid      =   0;
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
    public static function GenerateSerial($digit = 10){
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
        $offer = Offer::where('id', $this->offer_id)->first();
        return $offer;
    }
}