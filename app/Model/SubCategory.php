<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class SubCategory extends Model{
    protected $fillable = ['serial', 'category_id', 'name', 'deleted'];
    protected $table 	= "subcategory"; 
	public static function boot(){
        parent::boot(); 
        self::creating(function($model){
            $model->deleted 	=   0;
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
    public static function GenerateSerial($digit = 15){
        $chars 	= array(0,1,2,3,4,5,6,7,8,9);
        $max 	= count($chars) - 1; 
        while(1){
            $sn = '';
            for($i = 0; $i < $digit; $i++)
                $sn .=  $chars[rand(0, $max)];
            $apitoken   =   self::where('serial', $sn)->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;     
    }
    public function getCategory(){
        $category   =   Industry::where('id', $this->category_id)
                            ->first();
        return $category;
    }
}