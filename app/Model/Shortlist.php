<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Shortlist extends Model{
    protected $fillable = ['user_id','job_id','status'];
    protected $table    = "shortlist"; 
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
