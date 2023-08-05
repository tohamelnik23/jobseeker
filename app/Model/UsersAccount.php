<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use App\User;
use Session;
class UsersAccount extends Model 
{
    protected $primaryKey = 'account_id';
    protected $table = 'user_account';
	const CREATED_AT = 'user_account_created_at';
    const UPDATED_AT = 'user_account_updated_at';	
	
	protected $fillable = [ 'account_id',
        'name', 'firstname', 'lastname', 'profilepic', 'businessname', 'city',   'state', 'zip', 'caddress', 'oaddress', 'lat', 'lng', 'birthdate', 'headline', 'socialsecuritynumber', 'industry', 'start_stage','allow_creditcheck', 'coverletter', 'loan_fee', 'loan_amount', 'loan_enable']; 

	public static function boot(){
        parent::boot(); 
        self::creating(function($model){
			$model->start_stage 		= 0; 
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
	public function getSocialsecuritynumberAttribute($socialsecuritynumber)
	{
		if($socialsecuritynumber != NULL){
			try {
				$decrypted = decrypt($socialsecuritynumber);
			} catch (DecryptException $e) {
				$decrypted = NULL;
			}
			  return $decrypted;
		}
		 return $socialsecuritynumber;
	}
}