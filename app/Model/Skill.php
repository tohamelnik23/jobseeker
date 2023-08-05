<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Skill extends Model{
    protected $fillable = ['user_id','skill', 'description', 'deleted'];
    protected $table 	= "skills"; 
	
	public static function boot(){
        parent::boot(); 
        self::creating(function($model){
            $model->deleted 	    =   0;
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
	
    public function users() {
    	return $this->belongsToMany('App\User');
    }
	public function getSkillList($request,$limit=NUll,$offset=NULL){ 
		$result=array(); 
		$col= isset($request['order'][0]['column'])?$request['order'][0]['column']:'0';
		$dir= isset($request['order'][0]['dir'])?$request['order'][0]['dir']:'asc';
		$columns_valid = array(
			"skill", 
			"created_at",   
        ); 
        if(!isset($columns_valid[$col])) {
            $order = null;
        } else {
            $order = $columns_valid[$col];
        }
		
		$query = Skill::select("skills.*");
		if (!empty($request['search']['value'])) {
				$query->where('skills.skill', 'like', "%".$request['search']['value']."%");
		}
		
		if($order !=null) {
            $query->orderBy($order, $dir);
        }
		$result['num'] =  count($query->get());
		if(!empty($limit)){
			$query->take($limit)->skip($offset);
		}
		$result['result'] =  $query->get();
		return $result;	
	} 
}