<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class BorrowingChangeRequest extends Model{
    protected $fillable = ['amount','user_id', 'status'];
    protected $table    = "borrowing_changing_request";
    // 0: requsted 1: closed
    public static function boot(){
        parent::boot();
        self::creating(function($model){ 
            $model->status      =   0;
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