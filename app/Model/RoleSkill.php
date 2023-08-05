<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class RoleSkill extends Model{
    protected $primaryKey 	= 'id';
	protected $table 		= 'role_skills'; 
    protected $fillable 	= ['role', 'skill', 'deleted'];
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