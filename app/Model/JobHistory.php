<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class JobHistory extends Model{
    protected $fillable = ['job_title','job_company','job_start_date_year', 'job_end_date_year', 'job_start_date_month', 'job_end_date_month', 'job_description','user_id','is_deleted', 'serial'];
    protected $table    = "job_history";
    public static function boot(){
        parent::boot(); 
        self::creating(function($model){
            $model->is_deleted  =   0;
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
}
