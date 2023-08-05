<?php 
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class TimeSheet extends Model{
	protected $table 		= 'timesheets';
	protected $primaryKey 	= 'id';
	protected $fillable 	= ['offer_id', 'timesheets_time', 'timesheets_date', 'timesheets_rate', 'deleted', 'deposit_status'];
	public static function boot(){
	    parent::boot();
	    self::creating(function($model){
            $model->deleted 	   =  0;
			$model->deposit_status =  0;
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