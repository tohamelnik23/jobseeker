<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class JobSentWarning extends Model{
    protected $fillable = ['job_id','deleted', 'type'];
    protected $table    = "job_sent_warning";
    // 0: sent, 1: not sent
    public static function boot(){
        parent::boot();
        self::creating(function($model){ 
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
}