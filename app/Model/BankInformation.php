<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model; 
class BankInformation extends Model{
    protected $fillable = ['bank_name','routing_number','account_number', 'user_id', 'verification_status'];
    protected $table    = "bank_information";
    public static function boot(){
        parent::boot(); 
        self::creating(function($model){
            $model->verification_status  =   0; 
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