<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class SavedJob extends Model{
    protected $fillable = ['user_id','job_id','status'];
    protected $table    = "savedjobs";
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