<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class Role extends Model{
    protected $primaryKey 	= 'id';
	protected $table 		= 'roles'; 
    protected $fillable 	= ['role_title', 'hourly_rate_from','hourly_rate_to', 'is_deleted', 'user_id', 'serial', 'description', 'subcategory'];
    public static function boot(){
        parent::boot(); 
        self::creating(function($model){
            $model->is_deleted  =   0;
            $model->serial 	    =   self::GenerateSerial();
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
    public static function GenerateSerial($digit = 20){
        $chars 	= array(0,1,2,3,4,5,6,7,8,9);
        $max 	= count($chars) - 1;                                            
        while(1){
            $sn = '';
            for($i = 0; $i < $digit; $i++)
                $sn .= $chars[rand(0, $max)];
            $apitoken = self::where('serial', $sn)->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;         
    }
    public function getRoleSkills(){
        $role_skills =  RoleSkill::where('role', $this->id)
                                ->leftJoin('skills', "skills.id", "role_skills.skill")
                                ->select("skills.*")
                                ->where('role_skills.deleted', 0)
                                ->get();
        return $role_skills;
    }
    public function getHourlyDescription(){
        if($this->hourly_rate_from !== $this->hourly_rate_to){
            $description =  "$" . $this->hourly_rate_from . " /hr ~ " . "$". $this->hourly_rate_to . ' /hr';
        }
        else{
            $description =  "$" . $this->hourly_rate_from;
        }
        return $description;
    }
    public function getSuggestBudget(){
        if($this->hourly_rate_from !== $this->hourly_rate_to){
            return $this->hourly_rate_to;
        }
        else{
            return $this->hourly_rate_from;
        }
    } 
    public function subcategory(){
        $subcategory    =   SubCategory::where('id', $this->subcategory)
                                    ->first();
        return $subcategory;
	}
}