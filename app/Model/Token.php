<?php 
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Mainhelper;
use Config;
use Carbon\Carbon;
class Token extends Model{
	protected $table        = 'tokens';  
	protected $primaryKey   = 'tokens_id';
	protected $fillable     = ['tokens_token', 'tokens_account', 'tokens_serial'];  
 	public static function boot(){
        parent::boot();
        self::creating(function($model){
            $model->tokens_token     = self::GenerateToken();
            $model->tokens_serial    = self::GenerateSerial();
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
            $apitoken = self::where('tokens_serial', $sn)->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;
    }

    public static function GenerateToken($digit = 6){ 
        $now =  Carbon::now()->subDays(3);  
        $chars 	= array(0,1,2,3,4,5,6,7,8,9);
        $max 	= count($chars) - 1;
        while(1){
            $sn = '';
            for($i = 0; $i < $digit; $i++)
                $sn .= $chars[rand(0, $max)];
            
            $apitoken = self::where('tokens_token', $sn)
                            ->where('created_at', '>=', $now->toDateTimeString())
                            ->first(); 
            if(!isset($apitoken))
                break;
        }
        return $sn;
    }

}