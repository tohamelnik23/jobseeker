<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class Attachment extends Model{
	protected $table        =   'attachment';
	protected $primaryKey   =   'id';
	protected $fillable     =   ['type', 'ref_id', 'url', 'org_file_name', 'serial', 'deleted'];
    public static function boot(){
        parent::boot(); 
        self::creating(function($model){
            $model->deleted     =   0;
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
        $chars  	=   array(0,1,2,3,4,5,6,7,8,9);
        $max 	    =   count($chars) - 1; 
        while(1){
            $sn = '';
            for($i = 0; $i < $digit; $i++)
                $sn     .=  $chars[rand(0, $max)];
            $apitoken   =   self::where('serial', $sn)->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;
    }

    public function getURL(){
        $url = "";
        switch($this->type){
            case 'job':
                $item   =   Job::where('id', $this->ref_id)
                                ->first();
                break;
            case 'offer':
                $item   =   Offer::where('id', $this->ref_id)
                                ->first();
                break;
        }  
        if(isset($item)){
            $url =  Storage::disk('spaces')->url($this->type . '/'  .  $item->serial . '/' . $this->url);
        }  
        return $url;
    }
}