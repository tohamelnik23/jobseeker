<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Auth, Storage;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Model\Skill;
use App\Model\Media;
use App\Model\Certification;
use App\Model\Role;
use App\Model\Education;
use App\Model\Notification;
use App\Model\JobHistory;
use Carbon\Carbon;
use App\Model\Setting;
use App\Model\RoleSkill; 
use App\Model\DriverLicense; 
use App\Model\Invite;
use App\Model\Shortlist;
use App\Model\SavedFreelancer;
use App\Model\Proposal;
use App\Model\Message;
use App\Model\Decline;
use App\Model\MessageList;
use App\Model\Offer;
use App\Model\Archive;
use App\Model\Card;
use App\Model\Milestone;
use App\Model\DiscovergigTransaction;
use App\Model\Escrow;
use App\Model\Feedback;
use App\Model\BankInformation;
use App\Model\LoanRequest;
use App\Model\TimeSheet;
use App\Model\SubmitWork;
use App\Model\Job;
use App\Model\Industry;
use App\Model\Account_verification;
class User extends Authenticatable{
    use Notifiable;  
    protected $fillable = [
        'name', 'email', 'password', 'cphonenumber', 'serial' ,'desired_rate', 'start_stage', 'allow_creditcheck'
	];  
	public static function boot(){
        parent::boot(); 
        self::creating(function($model){
			//$model->start_stage 		= 0; 
			$model->serial 	    =   self::GenerateSerial();
        }); 
        self::created(function($model){
			$model->addSettingValue('notification_email', "no_email"); 
			$model->addSettingValue('notification_sms',  "no_sms");
			$model->save();
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
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];  
	public function getImage(){
		$media 	= 	Media::where('user_id', $this->id)
						->where('type', 'avtar')
						->first(); 
		if(isset($media)){
			return Storage::disk('spaces')->url( $media->path);
		}
		else{
			return  asset('img/avatar/placeholderAvatar.jpg');
		}
	}  
	public function accounts(){
        return $this->hasOne('App\Model\UsersAccount','account_id', 'id');
	} 
	public function getCards(){
		$cards 	= 	Card::where('user_id', $this->id)
						->where('deleted', 0)
						->where('status', 'verified')
						->get();
		return $cards;
	} 
	public function getDriverLicense(){
		$driverlicense 	= 	DriverLicense::where('user_id', $this->id)
										->first();
		return  $driverlicense;
	} 
	public function generateRandomString($length = 10) { 
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString; 
	}
	public function Updatehistory($olduser,$user) {
		foreach($user as $key => $input){
			if($olduser[$key] != $input || ($olduser[$key] == NULL && $input != NULL) ){
				DB::table('history')->insert([
					'user_id' => Auth::user()->id,
					'cname' => $key,
					'cvalue' => $input
				]);
			}
		}
	}
	public function avtarMedia() {
        return $this->hasOne('App\Model\Media')->where('type','avtar');;
    }  
	public function educations() {
        return $this->hasMany('App\Model\Education');
    } 
	public function transportations() {
        return $this->hasMany('App\Model\Transportation');
    } 
	public function getemployeeslist($request,$limit=NUll,$offset=NULL){ 
		$result=array(); 
		$col= isset($request['order'][0]['column'])?$request['order'][0]['column']:'0';
		$dir= isset($request['order'][0]['dir'])?$request['order'][0]['dir']:'asc';
		$q = $request['search']['value'];
		$columns_valid = array(
			"user_account.name", 
			"email", 
			"created_at",   
        ); 
        if(!isset($columns_valid[$col])) {
            $order = null;
        } else {
            $order = $columns_valid[$col];
        } 
		$query = User::select("users.*","user_account.*");
		$query->Join('user_account', function($join){
						$join->on('user_account.account_id', '=', 'users.id');
					});
		$query->where('users.role', 1);
		if (!empty($request['search']['value'])) { 
			$query->where(function ($query1) use ($q) {
                            $query1->where('user_account.name', 'like', "%".$q."%")
								->orWhere('users.email', 'like', "%".$q."%");
                            });
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
	public function getemployerslist($request,$limit=NUll,$offset=NULL){ 
		$result 	=	array(); 
		$col= isset($request['order'][0]['column'])?$request['order'][0]['column']:'0';
		$dir= isset($request['order'][0]['dir'])?$request['order'][0]['dir']:'asc';
		$q = $request['search']['value'];
		$columns_valid = array(
			"user_account.name", 
			"email", 
			"created_at",   
        ); 
        if(!isset($columns_valid[$col])) {
            $order = null;
        } else {
            $order = $columns_valid[$col];
        } 
		$query = User::select("users.*","user_account.*");
		$query->Join('user_account', function($join){
						$join->on('user_account.account_id', '=', 'users.id');
					});
		$query->where('users.role', 2);
		if (!empty($request['search']['value'])) {
			
			$query->where(function ($query1) use ($q) {
                            $query1->where('user_account.name', 'like', "%".$q."%")
								->orWhere('users.email', 'like', "%".$q."%");
                            });
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
	public function getSocialsecuritynumberAttribute($socialsecuritynumber){
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
	public function employesearch($skilld, $address, $lat, $lng){   
		$query 	= 	SELF::join('user_account', 'users.id', '=', 'user_account.account_id')
						->select("users.*",'user_account.*');
					//	->select("users.*",'user_account.*',DB::raw('( 3959 * acos( cos( radians('.$lat.') ) * cos( radians( user_account.lat ) ) * cos( radians( user_account.lng ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( user_account.lat ) ) ) ) AS distance'));
					
		$query->join('skill_user', function ($join)use($skilld) {
			$join->on('skill_user.user_id',"=",'users.id');
			$join->where('skill_user.skill_id', '=',$skilld);
		}); 
		//$query->having('distance', '<','100'); 
		$query->where('users.role', 1);
		return $query->get();	
	} 
	public function getCertifications(){
		$certifications 	= 	Certification::where('user_id',  $this->id)
										->where('is_deleted', 0)
										->get();
		return $certifications;
	} 
	public function getRoles(){
		$roles 	= 	Role::where('user_id',  $this->id)
						->where('is_deleted', 0)
						->get(); 
		return $roles; 
	}
	public function geteducations() {
		$educations 	= 	Education::where('user_id',  $this->id)
								->where('is_deleted', 0)
								->get();
		return $educations;
	}
	public function getjobhistories(){
		$jobhistories 	= 	JobHistory::where('user_id',  $this->id)
								->where('is_deleted', 0)
								->get(); 
		return $jobhistories;
	}
	public function getNotifications($type = "date_limited"){
		$user_id 						= 	$this->id;
		$limited_time 					=  	Carbon::now()->subDays(30);
		$limited_time_variable 			=	$limited_time->toDateTimeString();  
		$notifications_obj  			= 	Notification::where('notifications_touser',  $user_id)->orderBy("created_at", "desc");
		$latest_undreadnotification 	=	Notification::where('notifications_touser',  $user_id) 
												->select('created_at')
												->where('notifications_readby', 0)
												->orderBy("created_at")
												->first(); 
		if(isset($latest_undreadnotification)){
			if($latest_undreadnotification->created_at < $limited_time->toDateTimeString())
				$limited_time_variable 	=	$latest_undreadnotification->created_at; 
		}
		$notifications  =  $notifications_obj->get();
		 
		$result = array();
		$result['notfications']		= 	$notifications; 
		if(count($notifications)){
			$result['new_message'] 	= 	$notifications_obj->where('notifications_readby', 0)->count();
		}
		else{
			$result['new_message'] 	=  0;
		}
		return $result;
	} 
	public function getNewNotification( $user_type = 'superadmin' ){ 
		if($user_type == "superadmin")
			$new_notifications 	=	Notification::where('notifications_touser',  0) 
										->where('notifications_readby', 0)
										->count();
		else
			$new_notifications 	=		Notification::where('notifications_touser',  Auth::user()->id) 
											->where('notifications_readby', 0)
											->count(); 
		return $new_notifications;
	}
	public function getUnreadMessages(){
		$unread_messages 	=  	Message::where('to_user', $this->id)
										->where('message_read', 0)
										->count();
		return  $unread_messages;
	}
	public function addSettingValue($key, $value){
		Setting::addValue($this->id, $key, $value);
	} 
	public function getValue($key){
		$value = Setting::getValue($this->id, $key); 
		if($value == ""){
			switch($key){
				case 'notification_message_email_type':
				case 'notification_message_sms_type':
					$value = "all_activity";
					break;
				case 'notification_message_email_time':
				case 'notification_message_sms_time':
					$value = "every_15";
					break;
				case 'notification_message_email_sendtype':
				case 'notification_message_sms_sendtype':
					$value = "no";
					break;
				default:
					$value = "yes";
					break;
			}
		}
		return $value;
	}
	public static function CmpRoleSkill($a, $b) {
		return strcmp($a->match_counts, $b->match_counts);
	}
	// get best profiles
	public function getBestProfile($skills_list, $special_role = 1){
		$roles 			= $this->getRoles();
		$set_index 		= 0;
		$count_value 	= 0; 
		if($special_role){
			foreach($roles as $role){ 
				$role_skill_counts 	= 	RoleSkill::where('role', $role->id) 
											->where('role_skills.deleted', 0)
											->whereIn('skill', $skills_list)
											->count();
				$role->match_counts 	= 	$role_skill_counts;
			}  
			foreach($roles as $role_index => $role){
				if($role->match_counts > $count_value){
					$count_value 	=  $role->match_counts;
					$set_index  	=  $role_index;
				}
			}	
		}
		return $roles[$set_index];
	} 

	public function getRightProfile($category_id, $type = "subcategory"){
		 
		$subcategory_array = array();
		if($type == "subcategory"){
			$subcategory_array = [$category_id];
		}
		else{ 
			$category = 	Industry::where('id', $category_id)
								->first();  
			if(isset($category)){
				$subcategories      =   $category->subcategories;
				foreach($subcategories as $subcategory){
					$subcategory_array[]  = $subcategory->id;
				} 
			}
		}  
		$role 	= 	Role::where('user_id',  $this->id)
						->where('is_deleted', 0)
						->whereIn('subcategory',  $subcategory_array)
						->first(); 
		return $role; 
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
	public function checkInvited($job_id){
		$invite = 	Invite::where('user_id', $this->id)
						->where('job_id', $job_id)
						->count();
		
		if(!$invite){
			$invite  	= 	Proposal::where('user_id', $this->id)
								->where('job_id', $job_id)
								->count();
			if($invite)
				$invite = 2;
		} 
		return  $invite;
	} 
	public function checkSaved($client_id){
		$issaved 	= 	SavedFreelancer::where('user_id', $this->id)
							->where('client_id', $client_id)
							->where('status', 1)
							->count();
		return  $issaved;
	}
	public function checkShortlist($job_id){
		$issaved 		= 	Shortlist::where('user_id', $this->id)
								->where('job_id', $job_id)
								->where('status', 1)
								->count();
		return  $issaved;
	}
	public function checkArchived($job_id){
		$is_archived 	= 	Archive::where('user_id', $this->id)
									->where('job_id', $job_id)
									->where('status', 1)
									->count();
		return $is_archived;
	} 
	public function getProposals($status = 0){
		$count_proposals =  Proposal::where('user_id', $this->id)
									->where('status', $status)
									->count();
		return $count_proposals;
	}   
	public function getInvites($status = 0){
		$count_invites 	=  	Invite::where('user_id', $this->id)
									->where('status', $status)
									->count();
		return $count_invites;
	}
	public function getOffers($status = 0){
		$count_offers 	=	Offer::where('user_id', $this->id)
								->where('status', $status)
								->count();
		return $count_offers;
	}
	public function getDecline($job_id){
		$decline = 	Decline::where('job_id', $job_id)
							->where('decline_user', $this->id)
							->first();
		return $decline;
	}
	public function checkMessaged($job_id){
		$message_list =  	MessageList::where('job_id', $job_id)
									->where('to_user_id', $this->id)
									->first();
		return $message_list;
	}
	public function checkOffer($job_id){
		$offer =  Offer::where('job_id', $job_id)
						->where('user_id', $this->id)
						->first();
		return $offer;
	}
	// for employee
	public function countContract($type){
		$counts = 0; 
		switch($type){
			case 'hourly':
				$counts 	=   Offer::where('offers.status', '<>', 0)
									->where('offers.status', '<>', 10)
									->where('payment_type', $type)
									->where('user_id', $this->id)
									->count();
				break;
			case 'active_milestone':
				$counts 	=   Milestone::where('milestones.deposit_status', 1)
										->where('milestones.status', 'active')
										->join("offers", "offers.id", "milestones.offer_id")
										->where('offers.user_id', $this->id)
										->count();
				break;
			case 'awaiting_milestones':
				$counts 	=   Milestone::where('milestones.status', 'inactive')
										->join("offers", "offers.id", "milestones.offer_id")
										->where('offers.user_id', $this->id)
										->count();	
				break;
			case 'payment_requests':
				$counts 	=   SubmitWork::where('submit_work.user_id', $this->id) 
										->where('submit_work.status',  'active')
										->count();
				break;
		}
		return $counts;
	} 
	//  emplyee reports
	public function checkTotalReports($type){
		$total = 0;
		if($type == "in_progress"){
			//Get This week
			$star_of_week 	= 	Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
			$hourly_ids 	= 	TimeSheet::where('timesheets_date', '>=', $star_of_week)
									->join("offers", "offers.id", "timesheets.offer_id") 
									->select('timesheets.offer_id')
									->where('offers.user_id', $this->id)
									->groupBy('timesheets.offer_id')
									->where('deposit_status', '<>', '2')
									->get();
			 
			foreach($hourly_ids as $hourly_id){
				$timesheets  	= 	TimeSheet::where('offer_id', $hourly_id->offer_id)
										->where('timesheets_date',	'>=', 	$star_of_week)
										->join("offers", "offers.id", "timesheets.offer_id") 
										->select('timesheets.*', 'offers.amount')
										->get();
				
				$minutes    = 0;
				foreach($timesheets as $timesheet){
					list($hour, $minute)    =   explode(':', $timesheet->timesheets_time );
                    $minutes                +=  $hour * 60;
                    $minutes                +=  $minute;    
				}

				$hours_val   =  $minutes / 60;
                $hours_val   =  round($hours_val, 2); 
				$total_paid  =  $hours_val * $timesheet->amount;
				$total 		+=  $total_paid; 
			}
		}

		if($type == "in_review"){
			$star_of_week 	= 	Carbon::now()->subDays(7)->startOfWeek(Carbon::MONDAY)->toDateString();
			$end_of_week 	=	Carbon::now()->subDays(7)->endOfWeek(Carbon::SUNDAY)->toDateString(); 
			$hourly_ids 	= 	TimeSheet::where('timesheets_date', '>=', $star_of_week)
									->where('timesheets_date', '<=' , $end_of_week)
									->join("offers", "offers.id", "timesheets.offer_id") 
									->select('timesheets.offer_id')
									->where('offers.user_id', $this->id)
									->groupBy('timesheets.offer_id')
									->where('deposit_status', '<>', '2')
									->get();  
			foreach($hourly_ids as $hourly_id){
				$timesheets  	= 	TimeSheet::where('offer_id', $hourly_id->offer_id)
										->where('timesheets_date',	'>=', 	$star_of_week)
										->where('timesheets_date', '<=' , 	$end_of_week)
										->join("offers", "offers.id", "timesheets.offer_id") 
										->select('timesheets.*', 'offers.amount')
										->get();
				
				$minutes    = 0;
				foreach($timesheets as $timesheet){
					list($hour, $minute)    =   explode(':', $timesheet->timesheets_time );
					$minutes                +=  $hour * 60;
					$minutes                +=  $minute;    
				}

				$hours_val   =  $minutes / 60;
				$hours_val   =  round($hours_val, 2); 
				$total_paid  =  $hours_val * $timesheet->amount;
				$total 		+=  $total_paid;
			} 
			$submit_work_paid 	=   SubmitWork::where('submit_work.user_id', $this->id) 
										->where('submit_work.status',  'active')
										->sum('amount');  
			$total 		+=  $submit_work_paid;
		}
		if($type == "available"){
			$total_in 	= 	DiscovergigTransaction::where('user_id', $this->id)
									->where('status', 'available')
									->where('direction', 'in')
									->sum('amount');
			
			$total_out 	= 	DiscovergigTransaction::where('user_id', $this->id)
								->where('status', 'available')
								->where('direction', 'out')
								->sum('amount');
			$total = $total_in - $total_out;
		} 
		if($type == "pending"){
			$total_in 	= 	DiscovergigTransaction::where('user_id', $this->id)
									->where('status', 'pending')
									->where('direction', 'in')
									->sum('amount');
			
			$total_out 	= 	DiscovergigTransaction::where('user_id', $this->id)
								->where('status', 'pending')
								->where('direction', 'out')
								->sum('amount');
			$total = $total_in - $total_out;
		}
		$total =  round($total, 2);
		return $total;
	} 
	//employer reports
	public function checkTotalEscrow(){
		$total_in 	= 	Escrow::where('user_id', $this->id)
									->where('status', 'available')
									->where('direction', 'in')
									->sum('amount'); 
		$total_out 	= 	Escrow::where('user_id', $this->id)
							->where('status', 'available')
							->where('direction', 'out')
							->sum('amount');
		$total 	= 	$total_in - $total_out;					
		$total 	= 	round($total, 2);
		return $total;
	}
	public function getJobHistoyList(){
		if($this->role == 1)
			$offers 	= 	Offer::where('offers.user_id', $this->id)
								->leftJoin("jobs", "jobs.id", "offers.job_id")
								->select("offers.*", 'jobs.headline')
								->orderBy("offers.created_at", "desc")
								->orderBy("offers.status")
								->get();
		else
			$offers 	= 	Offer::where('employer_id', $this->id)
								->leftJoin("jobs", "jobs.id", "offers.job_id")
								->select("offers.*", 'jobs.headline')
								->get();
		
		return $offers;
	} 
	public function getBankInformation(){
		$bankinformation = 	BankInformation::where('user_id', $this->id)
										->first();
		return $bankinformation;
	}
	public function getLoanValue($type){
		$result 	= 	0;/*
		$total_paid =   DiscovergigTransaction::where('user_id', $this->id)
								->where('type', 'Loan Request')
								->where('status', 'available')
								->sum('amount');*/		
		$total_paid		=	$this->checkTotalReports('available');  
		$loan_fee 		=	100 - $this->accounts->loan_fee;
		switch($type){
			case 'total_loans_pending':
			case 'total_owed_pending':
				/*
				$loan_request_amount 	= 	LoanRequest::where(function ($query){
													$query->where('status',      "pending")
															->orWhere('status',    "paid");
												})
												->where('user_id', $this->id)
												->sum("agreed_amount");	*/
				$loan_request_amount 	= 	LoanRequest::where('status',  "pending")
												->where('user_id', $this->id)
												->sum("agreed_amount");  
				//$result 				=	$loan_request_amount - $total_paid; 
				if($type == "total_owed_pending"){
					$result =  $loan_request_amount * 100 / $loan_fee;
				}
				$result 				=	$result - $total_paid;
				if($result < 0)
					$result = 0;
				break;
			case 'total_loans_finished':
			case 'total_owed_finished': 
				/*
				$loan_request_amount 	= 	LoanRequest::where('status',  "paid")
														->where('user_id', $this->id)
														->sum("agreed_amount");	 
				$result 				=	$loan_request_amount - $total_paid;
				if($type == "total_owed_finished"){
					$result =  $result * 100 / $loan_fee;
				}
				*/
				$result 				=	(-1) * $total_paid;
				if($result < 0)
					$result = 0;
				break;
		}
		return round($result, 2);
	}
	public function checkPaymentMethod(){
		$card_count =  	Card::where('user_id', $this->id)
							->where('deleted', 0)
							->where('status', 'verified')
							->count();
		return $card_count;
	} 
	public function getFeedBackPoint(){
		$feedback_point = 	Feedback::where('user_id', $this->id)
								->avg('rate_total');
		$feedback_point =   round($feedback_point, 2);
		return $feedback_point;
	} 
	public function totalReviews(){
		$feedback_count = 	Feedback::where('user_id', $this->id)
								->count('rate_total');
		return  $feedback_count;
	} 
	public function total_job_posted($type = "all"){
		$total_jobs 	= 	0; 
		if($type == "all")
			$total_jobs = 	Job::where('user_id', $this->id)
								->where('status', '<>', 0)
								->count();
		if($type == "open")
			$total_jobs = 	Job::where('user_id', $this->id)
								->where('status', 1)
								->count();  
		if($type == "hired"){
			$total_jobs_offers	= 	Offer::where('employer_id', $this->id)
										->groupBy("job_id")
										->select("job_id")
										->where('status', '<>', 0)
										->get();
			$total_jobs  	= 	count($total_jobs_offers);
		}
		if($type == "finished"){
			$total_jobs	= 	Offer::where('employer_id', $this->id)  
								->where('status',   10)
								->get();
		}
		if($type == "progress"){
			$total_jobs	= 	Offer::where('employer_id', $this->id)  
										->where('status', '<>', 0)
										->where('status', '<', 10)
										->orderBy('start_time', 'desc')
										->get();
			//$total_jobs  	= 	count($total_progress); 
		} 
		return $total_jobs;
	} 
	public function getTotalSpent(){
		$total_escrow 	= 	Escrow::where('user_id', $this->id)
							->where('direction', 'out')
							->sum('amount');
		$total_escrow 	=	round($total_escrow, 2);
		return  $total_escrow;
	} 
	public function getTotalOffer($type = "all"){
		if($type == "all")
			$total_jobs_offers	= 	Offer::where('employer_id', $this->id)
										->where('status', '<>', 0)	
										->count(); 
		if($type == "open"){
			$total_jobs_offers	= 	Offer::where('employer_id', $this->id)
										->where('status', '<>', 0)
										->where('status', '<', 10)
										->count(); 
		}
		return $total_jobs_offers;
	} 
	public function getTotalHours( $type = "employer" ){  
		$result 		= 	array();
		if($type == "employer"){
			$timesheets  	= 	TimeSheet::where('offers.employer_id', $this->id)
										->join("offers", "offers.id", "timesheets.offer_id") 
										->select('timesheets.*')
										->get();
		}
		else{
			$timesheets  	= 	TimeSheet::where('offers.user_id', $this->id)
										->join("offers", "offers.id", "timesheets.offer_id") 
										->select('timesheets.*')
										->get();
		}
		
		$minutes    = 	0;
		$total_paid =	0;

		foreach($timesheets as $timesheet){
			list($hour, $minute)    =   explode(':', $timesheet->timesheets_time ); 
			$minute_item 	=   $hour * 60; 
			$minute_item    +=  $minute; 
			$minutes        +=  $minute_item;
			
			$hours_val   =  $minute_item / 60; 
			$hours_val   =  round($hours_val, 2); 
			
			$total  	 =  $hours_val * $timesheet->timesheets_rate;
			 
			$total_paid +=  $total;  
		}
		$hours       =  floor($minutes / 60); 
		$result['total_worked'] = $hours;
		$result['total_paid'] 	= $total_paid; 
		return $result;
	}  
	public function TotalEarnings(){ 
		 $total_out      =   DiscovergigTransaction::where('direction', 'in')
		 						->where('user_id', $this->id)
								->sum('amount'); 
		$total_out  =   round($total_out, 2);
		return $total_out; 
	} 
	public function getTotalJobs(){
		$total_jobs 	= 	Offer::where('user_id', $this->id)
								->where('status', '<>', 0)
								->count();
		return  $total_jobs;
	}
	public function verified_status($type){
		$tried 	= 	Account_verification::where('user_id', $this->id)
								->where('type', $type)
								->where('cleared', 0)
								->count();
		if($tried >= 10)
			return 0; 
		$account_verification 	= 	Account_verification::where('user_id', $this->id)
										->where('type', $type)
										->where('cleared', 0)
										->first();
		if(isset($account_verification)){
			if($account_verification->trying > 5)
				return 0;
		}
		return 1;
	} 
	public function getAllSkills(){
		$role_skills 	= 	RoleSkill::where('roles.user_id', $this->id)
								->join("roles", 'roles.id', 'role_skills.role')
								->select("role_skills.skill")
								->get();
		return  $role_skills;
	}
	public function getInterestingJobs(){
		$now 			=  Carbon::now()->subDays(5);
		$all_skills     = $this->getAllSkills();
        $skills_array   = array();
		foreach($all_skills as $all_skill){
			$skills_array[] = $all_skill->skill;
		} 
		$job_ids    =   array();
		$invites    =   Invite::where('user_id', $this->id)
							->where('created_at',  '>=', $now->toDateTimeString())
							->select("job_id")
							->get();
		foreach($invites as $invite){
			$job_ids[]  =   $invite->job_id;
		}
		$proposals  =   Proposal::where('user_id', $this->id)
							->where('created_at',  '>=', $now->toDateTimeString())
							->select("job_id")
							->get();
		foreach($proposals as $proposal){
			$job_ids[]  =   $proposal->job_id;
		}
		$jobs   =   Job::where('jobs.status', 1)
						->where('jobs.created_at',  '>=', $now->toDateTimeString()) 
						->whereNotIn('jobs.id', $job_ids)
						->join("job_skills", "job_skills.job_id", "jobs.id")
						->whereIn("job_skills.skill_id", $skills_array)
						->groupBy('jobs.id')
						->select("jobs.*")
						->take(5)
						->get(); 
        return $jobs;
	} 
}