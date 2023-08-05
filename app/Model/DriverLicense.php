<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model; 
class DriverLicense extends Model{
    protected $fillable = ['plate_number','state','expiration_year', 'expiration_month', 'verified', 'user_id'];
    protected $table    = "driver_license";
    public static function boot(){
        parent::boot(); 
        self::creating(function($model){
            $model->verified  =   '0'; 
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