<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class JobSkill extends Model{
    protected $fillable = ['job_id','skill_id','deleted'];
    protected $table    = "job_skills"; 
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
