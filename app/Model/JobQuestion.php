<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class JobQuestion extends Model{
    protected $fillable = ['question','job_id','deleted'];
    protected $table    = "job_question"; 
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
