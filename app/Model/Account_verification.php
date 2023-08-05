<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Account_verification extends Model{
    protected $fillable = ['type', 'code','user_id','cleared', 'trying'];
    protected $table    = "verifications";
    public static function boot(){
        parent::boot();
        self::creating(function($model){
            $model->code 	    =   self::GenerateSerial($model->user_id);
            $model->cleared     =   0;
            $model->trying      =   0;
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
    public static function GenerateSerial($user_id, $digit = 4){
        $chars 	= array(0,1,2,3,4,5,6,7,8,9);
        $max 	= count($chars) - 1; 
        while(1){
            $sn = '';
            for($i = 0; $i < $digit; $i++)
                $sn .=  $chars[rand(0, $max)];
            
            $apitoken   =   self::where('code', $sn)
                                ->where('user_id', $user_id)
                                ->where('cleared', 0)
                                ->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;     
    }
    public static function checkCode($user_id, $type, $code){
        
    }
}