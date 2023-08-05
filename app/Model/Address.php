<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Address extends Model{
    protected $fillable = ['address_name1','address_name2','address_deleted','address_city','address_state','address_zipcode', 'user_id'];
    protected $table    = "address";
    public static function boot(){
        parent::boot();
        self::creating(function($model){
            $model->address_deleted = 0;
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