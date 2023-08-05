<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class Setting extends Model{ 
    protected $table 		= 'settings';
	protected $primaryKey 	= 'id';
	protected $fillable 	= ['settings_key', 'settings_value', 'user_id' ,'deleted'];
	public static function boot(){ 
	    parent::boot();
	    self::creating(function($model){
	    	$model->deleted 	= 0; 
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
    public static function addValue($user_id, $key, $value){ 
    	self::updateOrCreate([
            'settings_key' 			=> $key,
            'user_id'               => $user_id
    	],[
    		'settings_value' 	  	=> $value,
    		'deleted' 		        => 0,
    	]); 
    }
    public static function getValue($user_id, $key){
		$result = 	self::where('settings_key', $key)
                        ->where('deleted', 0)
                        ->where('user_id', $user_id)
			    		->first();
		if(isset($result))
			return $result->settings_value;
		else
			return "";
    }
}