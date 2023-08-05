<?php 
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class MasterSetting extends Model{
	protected $table 		= 'master_settings';
	protected $primaryKey 	= 'id';
	protected $fillable 	= ['setting_key', 'setting_value', 'deleted'];
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
    public static function addValue($key, $value){
    	self::updateOrCreate([
    		'setting_key' 			=> $key,
    	],[
    		'setting_value' 	  	=> $value,
    		'deleted' 		=> 0,
    	]);
    }
    public static function getValue($key){
		$result = 	self::where('setting_key', $key)
			    		->where('deleted', 0)
			    		->first(); 
		if(isset($result))
			return $result->setting_value;
		else
			return "";
    }
}