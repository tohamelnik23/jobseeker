<?php
namespace App\Http\Controllers\Employer\Jobs;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Model\UsersAccount;
use App\Model\Media;
use App\Model\Skill;
use App\Model\JobSkill;
use App\Model\Job;
use App\Model\Skillquestion;
use App\Model\Skillquestionoption;
use App\Model\JobQuestion;
use App\Model\Question;
use App\Model\Answer;
use App\Model\Proposal;
use App\Model\Vote;
use App\Model\SavedFreelancer;
use App\Model\Shortlist;
use App\Model\Archive;
use App\Model\Invite;
use App\Model\Industry;
use App\Model\Decline;
use App\Model\Offer;
use App\Model\MessageList;
use App\Model\Message;
use App\Model\Card;
use App\Model\Milestone;
use App\Model\Transaction;
use App\Model\Escrow;
use App\Model\MasterSetting;
use App\Model\Notification;
use App\Model\SubCategory;
use App\Model\Attachment;
use App\Helpers\Mainhelper;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
class JobsController extends BaseController{ 
    public function __construct(){
		$this->middleware(array('auth','employer'));
	} 
	public function myjobs(){
		$user 				= 	Auth::user();
		$this->user 		= 	User::find($user->id); 
		$this->draft_jobs 	= 	Job::where('user_id',$user->id)
									->where('status', 0)
									->get();   
		$this->posted_jobs 	= 	Job::where('user_id',$user->id)
									->where('status', 1)
									->get();   
        return view('employer/jobs/myjobs',$this->data);
	} 
	public function mypostings(Request $request){
		$user 				= 	Auth::user();
		$mypostings_obj 	= 	Job::where('user_id', $user->id)
									->orderBy("updated_at", 'desc')
									->where('status', '<>', 3);
		if($request->title){
			$mypostings_obj 	=   $mypostings_obj->where('headline', 'like' ,'%' . $request->title . '%');
		}

		$this->post_type = "all";
		if($request->post_type){
			$this->post_type = $request->post_type; 
		} 
		if($this->post_type !== "all"){
			$mypostings_obj 	=   $mypostings_obj->where('type',  $this->post_type );
		}
		
		$this->payment 	 = "all";
		if($request->payment){
			$this->payment 	= $request->payment;
		}

		if($this->payment !== "all"){
			$mypostings_obj 	=   $mypostings_obj->where('payment_type',  $this->payment );
		}
		
		if($request->post_status)
			$this->post_status =  	$request->post_status;
		else
			$this->post_status = 	[];
		
		$post_status = [];
		foreach($this->post_status as $post_array){
			if($post_array == "drafts") $post_status[] = 0;
			if($post_array == "open") 	$post_status[] = 1;
			if($post_array == "closed") $post_status[] = 2;
		}
		if(count( $post_status )){
			$mypostings_obj 	=   $mypostings_obj->whereIn('status',  $post_status);
		}
		$mypostings 		=	$mypostings_obj->paginate(5);
		$this->mypostings 	=	$mypostings;
		return view('employer/jobs/mypostings',$this->data);
	} 
	public function contracts(Request $request){
		$user 				= 	Auth::user();
		$this->contracts 	= 	Offer::where('employer_id', $user->id)
									->where('status', '<>', 0)
									->paginate(10); 
		return view('employer/jobs/contracts',$this->data);
	} 
	public function postshift(Request $request){
		$user 					= 	Auth::user();
		$this->user 			= 	User::find($user->id);
		$this->skills   		= 	Skill::where('deleted', 0)->get(); 
		$this->industries 		= 	Industry::where('deleted', 0)->get(); 
		$this->subcategories	= 	SubCategory::where('deleted', '0')->get();
		$this->jobpost_type		= 	"new";
		return view('employer/jobs/postshift', $this->data);
	} 
	public function postgig(Request $request){
		$user 					= 	Auth::user();
		$this->user 			= 	User::find($user->id);
		$this->skills   		= 	Skill::where('deleted', 0)->get(); 
		$this->industries 		= 	Industry::where('deleted', 0)->get(); 
		$this->subcategories	= 	SubCategory::where('deleted', '0')->get();
		$this->jobpost_type		= 	"new";
		return view('employer/jobs/postjob',$this->data);
	}
	public function addjob(Request $request){ 
		$use_my_location 	= 	"no"; 
		if($request->use_my_location){
			$use_my_location = "yes";
		} 
		if($use_my_location == "yes"){
			$validator 	= 	Validator::make($request->all(),  [
								'headline'        => 'required | max : 256 | min: 3',
								'description'     => 'required | max : 65535 | min: 3', 
								'subcategory'	  => 'required | max : 15 | min: 15', 
								'location_type'	  => 'required | max : 20', 
								'post_status'     => 'required | numeric',
								'job_type'		  => 'required | max : 10 | min: 3',
								'payment_type'    => 'required | max : 20 | min: 3'
							]);
		}
		else{
			$validator 	= 	Validator::make($request->all(),  [
								'headline'        => 'required | max : 256 | min: 3',
								'description'     => 'required | max : 65535 | min: 3',
								'address1'		  => 'required | max : 256 | min: 3',
								'address2'		  => 'nullable | max : 256 | min: 3',
								'state'		  	  => 'nullable | max : 256',
								'subcategory'	  => 'required | max : 15 | min: 15',
								'zip'		  	  => 'required | max : 256',
								'location_type'	  => 'required | max : 20',
								'city'		  	  => 'required | max : 256', 
								'post_status'     => 'required | numeric',
								'job_type'		  => 'required | max : 10 | min: 3',
								'payment_type'    => 'required | max : 20 | min: 3'
							]);
		}
        if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "Please provide more details."], 200);

		if($use_my_location == "yes"){
			$address1 	=  	Auth::user()->accounts->caddress;
			$address2 	= 	Auth::user()->accounts->oaddress;
			$state 		= 	Auth::user()->accounts->state;
			$zip 		=	Auth::user()->accounts->zip;
			$city 		= 	Auth::user()->accounts->city;
		}
		else{
			$address1 	= $request->address1;
			$address2 	= $request->address2;
			$state 		= $request->state;
			$zip 		= $request->zip;
			$city 		= $request->city;	
		}
		if($request->job_type == "shift"){
			$validator = Validator::make($request->all(),  [
				'duration_type'         => 'required',
				'duration'     			=> 'required', 
				'job_end_time'		  	=> 'required',
				'job_date'				=> 'required',
				'job_time'				=> 'required'
			]);
		}
		else{
			$validator = Validator::make($request->all(),  [
				'job_date'		  => 'required | max : 256 | min: 3',
				'job_time'		  => 'required | max : 256 | min: 3',
			]);
		}
		if($validator->fails()){
			return response()->json(['status' => 0, 'msg' => "Please provide more details."], 200); 
		}

		if($request->estimted_budget == null){
			if($request->payment_type == "hourly"){
				$validator = Validator::make($request->all(),  [ 
					'custom_hourly_from'    => 'required | numeric',
					'custom_hourly_to'     	=> 'required | numeric'
				]);
			}
			else{
				$validator = Validator::make($request->all(),  [ 
					'custom_fixed_from'    => 'required | numeric',
					'custom_fixed_to'      => 'required | numeric'
				]);
			}
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "Please provide more details."], 200); 
		}
		$address 	=	$zip . ", " . $address1 . ", " . $city  . "," . $state . ",USA"; 
		$prepAddr 	= 	str_replace(' ','+',$address);  
		$geocode	=	file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyCJgcL_4zdeEI7q4E-crcMP19Jx8YCbWR8'); 
		$output		= 	json_decode($geocode);
		if($output->status == 'OK'){
			$latitude 	= 	$output->results[0]->geometry->location->lat;
			$longitude 	= 	$output->results[0]->geometry->location->lng; 
		}
		else{
			$latitude 	= null;
			$longitude	= null;
		} 
		$subcategory    		= 	SubCategory::where('serial', $request->subcategory)->first(); 
		if(!isset($subcategory)){
			return response()->json(['status' => 0, 'msg' => "Invalid request."], 200); 
		}

		$start_value 			= 	null;
		$end_value   			=	null;
		if($request->estimted_budget !== null){ 
		}
		else{
			if($request->payment_type == "hourly"){
				$start_value = $request->custom_hourly_from;
				$end_value   = $request->custom_hourly_to;
			}
			else{
				$start_value = $request->custom_fixed_from;
				$end_value   = $request->custom_fixed_to;
			}
		}

		if($request->job_type == "shift"){
			foreach($request->job_date as $shift_date_index => $req_job_date){
				$job_date 				=  	date("Y-m-d", strtotime($request->job_date[$shift_date_index]));
				$job_time 				=  	date("H:i:s", strtotime($request->job_time[$shift_date_index]));
				$job_end_date 			= 	$job_date;
				$job_end_time 			=  	date("H:i:s", strtotime($request->job_end_time[$shift_date_index]));
				$job_end_date_time  	=   $job_date  . " " . $job_end_time;
				// 1. create jobs
				$job 	=  	Job::create([
					'user_id' 		 		=> Auth::user()->id,
					'job_date'  	 		=> $job_date,
					'job_time'  	 		=> $job_time,
					'job_date_time'  		=> $job_date . " " . $job_time,
					'status'    	 		=> $request->post_status,
					'zip'			 		=> $zip,
					'city'			 		=> $city, 
					'state'			 		=> $state,
					'address2'		 		=> $address2,
					'address1'		 		=> $address1,
					'description'	 		=> $request->description,
					'headline'			 	=> $request->headline,
					'lng'			 		=> $longitude,
					'lat'			 		=> $latitude, 
					'budget_start'	 		=> $start_value,
					'budget_end'	 		=> $end_value,
					'estimted_budget'		=> $request->estimted_budget,
					'payment_type'	 		=> $request->payment_type,
					'location_type'	 		=> $request->location_type,
					'type'			 		=> $request->job_type,
					'job_type'       		=> $subcategory->id,

					'shift_end_date'  		=> $job_end_date,
					'shift_end_time'  		=> $job_end_time,
					'shift_end_date_time'	=> $job_end_date_time,
					'duration'  			=> $request->duration[$shift_date_index], 
					'duration_type'  		=> $request->duration_type[$shift_date_index]
				]);
				//2. add skills
				if($request->skills !== null){
					foreach($request->skills as $request_skill){
						JobSkill::updateOrCreate([
							'job_id' 	=> $job->id,
							'skill_id'	=> $request_skill,
						],[
							'deleted'  => 0
						]);
					}
				}
				//3. add questions
				//3.1 assosiate question to the skills
				if($request->suggested_question !== null){
					foreach($request->suggested_question as $suggested_question){
						JobQuestion::updateOrCreate([
							'question'  => $suggested_question,
							'job_id' 	=> $job->id,
						],[
							'deleted'   => 0
						]);
					}
				}
				//3.2 create custom question
				if($request->custom_question !== null){
					foreach($request->custom_question as $custom_quesion){
						$question = Question::create([
							'question' => $custom_quesion,
							'type' 	   => 0,
							'user_id'  => Auth::user()->id
						]); 
						JobQuestion::updateOrCreate([
							'question'  => $question->serial,
							'job_id' 	=> $job->id,
						],[
							'deleted'   => 0
						]);
					}
				}
				//Notification
				if($request->post_status == 1){
					Notification::create([
						'notifications_fromuser' 	=>  0,
						'notifications_touser'		=>  Auth::user()->id,
						'notifications_value'		=>  'Your job "' . $job->headline . '" is posted and  should start receiving proposals.',
						'notification_ref'          =>  $job->serial,
						'notifications_type'		=> 'job_posted'
					]);
					if($job->type == "gig")
						Session::flash('message', '	YOUR GIG POSTING IS LIVE');
					else
						Session::flash('message', '	YOUR SHIFT POSTING IS LIVE');
					$url = 	route('employer.jobs.mainaction', [$job->serial, 'job-details']);
				}
				else{
					$url = 	route('employer.jobs');
				}
			}
		}
		else{
			$job_date 				=  	date("Y-m-d", strtotime($request->job_date));
			$job_time 				=  	date("H:i:s", strtotime($request->job_time));  
			$job_end_date 			= 	null;
			$job_end_time 			= 	null;
			$job_end_date_time 		= 	null;

			// 1. create jobs
			$job 	=  	Job::create([
				'user_id' 		 		=> Auth::user()->id,
				'job_date'  	 		=> $job_date,
				'job_time'  	 		=> $job_time,
				'job_date_time'  		=> $job_date . " " . $job_time,
				'status'    	 		=> $request->post_status,
				'zip'			 		=> $zip,
				'city'			 		=> $city, 
				'state'			 		=> $state,
				'address2'		 		=> $address2,
				'address1'		 		=> $address1,
				'description'	 		=> $request->description,
				'headline'			 	=> $request->headline,
				'lng'			 		=> $longitude,
				'lat'			 		=> $latitude, 
				'budget_start'	 		=> $start_value,
				'budget_end'	 		=> $end_value,
				'estimted_budget'		=> $request->estimted_budget,
				'payment_type'	 		=> $request->payment_type,
				'location_type'	 		=> $request->location_type,
				'type'			 		=> $request->job_type,
				'job_type'       		=> $subcategory->id,

				'shift_end_date'  		=> $job_end_date,
				'shift_end_time'  		=> $job_end_time,
				'shift_end_date_time'	=> $job_end_date_time,
				'duration'  			=> $request->duration, 
				'duration_type'  		=> $request->duration_type
			]);
			//2. add skills
			if($request->skills !== null){
				foreach($request->skills as $request_skill){
					JobSkill::updateOrCreate([
						'job_id' 	=> $job->id,
						'skill_id'	=> $request_skill,
					],[
						'deleted'  => 0
					]);
				}
			}
			//3. add questions
			//3.1 assosiate question to the skills
			if($request->suggested_question !== null){
				foreach($request->suggested_question as $suggested_question){
					JobQuestion::updateOrCreate([
						'question'  => $suggested_question,
						'job_id' 	=> $job->id,
					],[
						'deleted'   => 0
					]);
				}
			}
			//3.2 create custom question
			if($request->custom_question !== null){
				foreach($request->custom_question as $custom_quesion){
					$question = Question::create([
						'question' => $custom_quesion,
						'type' 	   => 0,
						'user_id'  => Auth::user()->id
					]); 
					JobQuestion::updateOrCreate([
						'question'  => $question->serial,
						'job_id' 	=> $job->id,
					],[
						'deleted'   => 0
					]);
				}
			}
			//Notification
			if($request->post_status == 1){
				Notification::create([
					'notifications_fromuser' 	=>  0,
					'notifications_touser'		=>  Auth::user()->id,
					'notifications_value'		=>  'Your job "' . $job->headline . '" is posted and  should start receiving proposals.',
					'notification_ref'          =>  $job->serial,
					'notifications_type'		=> 'job_posted'
				]);
				if($job->type == "gig")
					Session::flash('message', '	YOUR GIG POSTING IS LIVE');
				else
					Session::flash('message', '	YOUR SHIFT POSTING IS LIVE');
				$url = 	route('employer.jobs.mainaction', [$job->serial, 'job-details']);
			}
			else{
				$url = 	route('employer.jobs');
			}
		}

		return response()->json(['status' => 1, 'msg' => "success.", 'url' => $url ], 200); 
	} 
	public function jobedit(Request $request, $id){
		$job 	=	  Job::where("serial", $id)
						->where('user_id', Auth::user()->id)
						->first(); 
		if(!isset($job))
			abort(404);
		$this->job 		= $job; 
		$user 			= Auth::user();
		$this->user 	= User::find($user->id);
		if($request->isMethod('post')){ 
			$use_my_location 	= 	"no"; 
			if($request->use_my_location){
				$use_my_location = "yes";
			}
			if($use_my_location == "yes"){
				$validator 	= 	Validator::make($request->all(),  [
									'headline'        => 'required | max : 256 | min: 3',
									'description'     => 'required | max : 65535 | min: 3', 
									'subcategory'	  => 'required | max : 15 | min: 15', 
									'location_type'	  => 'required | max : 20', 
									'job_date'		  => 'required | max : 256 | min: 3',
									'job_time'		  => 'required | max : 256 | min: 3',
									'post_status'     => 'required | numeric', 
									'payment_type'    => 'required | max : 20 | min: 3'
								]);
			}
			else{
				$validator 	= 	Validator::make($request->all(),  [
									'headline'        => 'required | max : 256 | min: 3',
									'description'     => 'required | max : 65535 | min: 3',
									'address1'		  => 'required | max : 256 | min: 3',
									'address2'		  => 'nullable | max : 256 | min: 3',
									'state'		  	  => 'nullable | max : 256',
									'subcategory'	  => 'required | max : 15 | min: 15',
									'zip'		  	  => 'required | max : 256',
									'location_type'	  => 'required | max : 20',
									'city'		  	  => 'required | max : 256',
									'job_date'		  => 'required | max : 256 | min: 3',
									'job_time'		  => 'required | max : 256 | min: 3',
									'post_status'     => 'required | numeric', 
									'payment_type'    => 'required | max : 20 | min: 3'
								]);
			}
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "Please provide more details."], 200); 
			if($job->type == "shift"){
				$validator = Validator::make($request->all(),  [ 
					'duration_type'         => 'required | max : 256 | min: 3',
					'duration'     			=> 'required | numeric', 
					'job_end_time'		  	=> 'required | max : 256 | min: 3', 
				]); 
				if($validator->fails())
					return response()->json(['status' => 0, 'msg' => "Please provide more details."], 200); 
			}			
			if($use_my_location == "yes"){
				$address1 	=  	Auth::user()->accounts->caddress;
				$address2 	= 	Auth::user()->accounts->oaddress;
				$state 		= 	Auth::user()->accounts->state;
				$zip 		=	Auth::user()->accounts->zip;
				$city 		= 	Auth::user()->accounts->city;
			}
			else{
				$address1 	= $request->address1;
				$address2 	= $request->address2;
				$state 		= $request->state;
				$zip 		= $request->zip;
				$city 		= $request->city;	
			}
			if($request->estimted_budget == null){
				if($request->payment_type == "hourly"){
					$validator = Validator::make($request->all(),  [ 
						'custom_hourly_from'    => 'required | numeric',
						'custom_hourly_to'     	=> 'required | numeric'
					]);
				}
				else{
					$validator = Validator::make($request->all(),  [ 
						'custom_fixed_from'    => 'required | numeric',
						'custom_fixed_to'      => 'required | numeric'
					]);
				}
				if($validator->fails())
					return response()->json(['status' => 0, 'msg' => "Please provide more details."], 200); 
			}
			$address 	=	$request->zip . ", " . $request->address1 . ", " . $request->city  . "," . $request->state . ",USA"; 
			$prepAddr 	= 	str_replace(' ','+',$address);  
			$geocode	=	file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyCJgcL_4zdeEI7q4E-crcMP19Jx8YCbWR8'); 
			$output		= 	json_decode($geocode);    
			if($output->status == 'OK'){
				$latitude 	= $output->results[0]->geometry->location->lat;
				$longitude 	= $output->results[0]->geometry->location->lng; 
			}
			else{
				$latitude 	= null;
				$longitude	= null;
			}

			$job_date 			=  	date("Y-m-d", strtotime($request->job_date));		 
			$job_time 			=  	date("H:i:s", strtotime($request->job_time)); 
			$job_end_date 		= 	null;
			$job_end_time 		= 	null;
			$job_end_date_time 	= 	null;

			if($request->job_type == "shift"){
				$job_end_date 		=  	$job_date;
				$job_end_time 		=  	date("H:i:s", strtotime($request->job_end_time));
				$job_end_date_time  =   $job_end_date  . " " . $job_end_time;
			}

			$start_value 		= null;
			$end_value   		= null; 
			if($request->estimted_budget !== null){
			}
			else{
				if($request->payment_type == "hourly"){
					$start_value = $request->custom_hourly_from;
					$end_value   = $request->custom_hourly_to;
				}
				else{
					$start_value = $request->custom_fixed_from;
					$end_value   = $request->custom_fixed_to;
				}
			}
			$subcategory    = 	SubCategory::where('serial', $request->subcategory)->first(); 
			if(!isset($subcategory)){
				return response()->json(['status' => 0, 'msg' => "Invalid request."], 200); 
			}
			// 1. create jobs
			$job->update([
					'job_date'  	 		=> $job_date,
					'job_time'  	 		=> $job_time,
					'job_date_time'  		=> $job_date . " " . $job_time,
					'status'    	 		=> $request->post_status,
					'zip'			 		=> $zip,
					'city'			 		=> $city, 
					'state'			 		=> $state,
					'address2'		 		=> $address2,
					'address1'		 		=> $address1,
					'description'	 		=> $request->description,
					'headline'		 		=> $request->headline,
					'lng'			 		=> $longitude,
					'lat'			 		=> $latitude, 
					'budget_start'	 		=> $start_value,
					'budget_end'	 		=> $end_value,
					'job_type'       		=> $subcategory->id,
					'estimted_budget'		=> $request->estimted_budget,
					'location_type'	 		=> $request->location_type,
					'payment_type'	 		=> $request->payment_type,

					'shift_end_date'  		=> $job_end_date,
					'shift_end_time'  		=> $job_end_time,
					'shift_end_date_time'	=> $job_end_date_time,
					'duration'  			=> $request->duration,
					'duration_type'  		=> $request->duration_type 
			]); 
			//2. add skills
			JobSkill::where('job_id', $job->id)
					->update([
						'deleted'    => 1
					]); 
			if($request->skills !== null){
				foreach($request->skills as $request_skill){
					JobSkill::updateOrCreate([
						'job_id' 	=> $job->id,
						'skill_id'	=> $request_skill,
					],[
						'deleted'  => 0
					]);
				}
			}
			//4. Add Files 
			//4.1 add/update org file
			Attachment::where('type', 'job')
						->where('ref_id',  $job->id)
						->where('deleted', 0)
						->update([
							'deleted' => 1
						]);
			if($request->org_attachments){				
				foreach($request->org_attachments as $org_attachment){
					Attachment::where('type', 'job')
						->where('ref_id',  $job->id)
						->where('serial', $org_attachment)
						->update([
							'deleted' => 0
						]);
				}				
			}
			//4.2 add new file
			if ($request->hasFile('attachment')) {
				foreach($request->attachment as $fileitem){
					$filename 				= 	$fileitem->getClientOriginalName();
					$generated_filename 	= 	Mainhelper::generateRandomFilename( "job/" . $job->serial , $filename);  
					$newfilename 			= 	'job/' . $job->serial . '/'  . $generated_filename;
					$newfilename_array[] 	= 	$newfilename;
					Storage::disk('spaces')->put($newfilename, file_get_contents($fileitem->getRealPath()), 'public');

					$attachment 					= 	new Attachment();
					$attachment->type   			= 	"job";
					$attachment->ref_id				=	$job->id;
					$attachment->org_file_name   	= 	$fileitem->getClientOriginalName();
					$attachment->url 				= 	$generated_filename;
					$attachment->save();
				}
			}
			//3. add questions
			//3.1 assosiate question to the skills 
			JobQuestion::where('job_id', $job->id)
			->update([
				'deleted'    => 1
			]);			
			if($request->suggested_question !== null){
				foreach($request->suggested_question as $suggested_question){
					JobQuestion::updateOrCreate([
						'question'  => $suggested_question,
						'job_id' 	=> $job->id,
					],[
						'deleted'   => 0
					]);
				}
			}
			//3.2 create custom question
			if($request->custom_question !== null){
				foreach($request->custom_question as $custom_quesion){
					$question = Question::updateOrCreate([
						'question' => $custom_quesion,
						'type' 	   => 0,
						'user_id'  => Auth::user()->id
					],[ 
					]); 
					JobQuestion::updateOrCreate([
						'question'  => $question->serial,
						'job_id' 	=> $job->id,
					],[
						'deleted'   => 0
					]); 
				}
			} 
			Notification::create([
				'notifications_fromuser' 	=>  0,
				'notifications_touser'		=>  Auth::user()->id,
				'notifications_value'		=>  'Your job "' . $job->headline . '" is modified.',
				'notification_ref'          =>  $job->serial,
				'notifications_type'		=> 'job_modified'
			]);  
			if($request->post_status == 1){
				$url = 	route('employer.jobs.mainaction', [$job->serial, 'job-details']);
			}
			else{
				$url = 	route('employer.jobs');
			}
			return response()->json(['status' => 1, 'msg' => "success.", 'url' => $url ], 200); 
		} 
		$this->skills   		= 	Skill::where('deleted', 0)->get();
		$this->industries 		= 	Industry::where('deleted', 0)->get();
		$this->subcategories	= 	SubCategory::where('deleted', '0')->get(); 
		$this->jobpost_type		=  "edit";

		if($job->type == "gig")
			return view('employer/jobs/postjob',$this->data);
		else
			return view('employer/jobs/postshift', $this->data);
	}
	
	public function jobclose(Request $request, $id){
		$job 	=	  Job::where("serial", $id)
						->where('user_id', Auth::user()->id)
						->first(); 
		if(!isset($job))
			return response()->json(['status' => 0, 'msg' => "This job is closed"], 200); 
		$validator = Validator::make($request->all(),  [ 
			'reason'        => 'required | numeric',
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "Please provide more details."], 200); 
		$job->closeJob();
		return response()->json(['status' => 1, 'msg' => "success."], 200);
	} 
	public function jobdelete(Request $request, $id){
		$job 	=	Job::where("serial", $id)
						->where('user_id', Auth::user()->id)
						->first(); 
		if(!isset($job))
			return response()->json(['status' => 0, 'msg' => "This job is closed"], 200);  
		$job->update([
			'status' => 3
		]);
		if($job->type == "gig")
			Session::flash('message', '	Your gig posting is closed successfully.');
		else
			Session::flash('message', '	Your shift posting is closed successfully.'); 
		return response()->json(['status' => 1, 'msg' => "success."], 200);
	} 
	public function repost(Request $request, $id){
		$job 	=	  Job::where("serial", $id)
						->where('user_id', Auth::user()->id)
						->first(); 
		if(!isset($job))
			abort(404);
		$this->job = $job;
		$user 				= 	Auth::user();
		$this->user 		= 	User::find($user->id);
		$this->skills   	= 	Skill::where('deleted', 0)->get();
		$this->industries 	= 	Industry::where('deleted', 0)->get();
		$this->subcategories= 	SubCategory::where('deleted', '0')->get(); 
		$this->jobpost_type	= 	"new"; 
		if($job->type == "gig")
			return view('employer/jobs/postjob',$this->data);
		else
			return view('employer/jobs/postshift', $this->data);
	} 
	public function mainaction(Request $request, $job_id, $subrequest){
		$job 	=	  Job::where("serial", $job_id)
						->where('user_id', Auth::user()->id)
						->first();  
		if(!isset($job))
			abort(404);  
		$suggested_url = ['job-details', 'suggested', 'pending', 'hires', 'saved', 'applicants', 'shortlisted', 'messaged', 'archived', 'offers', 'hired']; 
		if(!in_array( $subrequest, $suggested_url)){
			abort( 404 );
		}              
		$this->job 			= 	$job;
		$this->subrequest 	= 	$subrequest;

		switch($subrequest){
			case 'job-details':
				$this->job_tab 	= "job_details"; 
				$this->view_way = "private";
				return view('employer/jobs/jobdetails',$this->data);
				break;
			case 'suggested':
			case 'pending':
			case 'hires':
			case 'saved': 
				$this->job_skills 	= 	$job->getSkills(1); 
				$this->job_tab 		= 	"invite"; 
				if($subrequest == "suggested"){ 
					$proposal_users =   $job->getProposals(1);  
					$freelancers 	=   User::select("users.*")
											->join('user_account', 'user_account.account_id', 'users.id')
											->where('users.role', '1')
											->where('start_stage', '10')
											->whereNotIn('users.id', $proposal_users)
											->where('users.availability', 'active')
											->paginate(20);
					$this->freelancers = $freelancers;
				} 
				if($subrequest == "saved"){
					$freelancers =  User::select("users.*", "savedfreelancer.notes")
										->join('user_account', 'user_account.account_id', 'users.id') 
										->join('savedfreelancer', 'savedfreelancer.user_id', 'users.id')
										->where('users.role', '1')
										->where('start_stage', '10')
										->where('savedfreelancer.status', '1')
										->where('savedfreelancer.client_id', Auth::user()->id)
										->paginate(20); 
					$this->freelancers = $freelancers;
				}
				if($subrequest == "pending"){
					$freelancers =  User::select("users.*")
										->join('user_account', 'user_account.account_id', 'users.id') 
										->join('invites', 'invites.user_id', 'users.id') 
										->where('invites.job_id', $job->id)
										->where('invites.status', 0)
										->paginate(20); 
					$this->freelancers = $freelancers;		 
				}
				if($subrequest == "hires"){ 
					$freelancers 	=   User::select("users.*")
											->join("offers", "offers.user_id", "users.id")
											->where('offers.employer_id', $job->user_id)
 											->where('offers.status', '<>' ,0)
											->where('offers.job_id', '<>' , $job->id)
											->groupBy("offers.user_id")
										 	->get();
					$this->freelancers = $freelancers;
				}
				return view('employer/jobs/invite',$this->data);
				break;
			case 'applicants':
			case 'shortlisted':
			case 'messaged':
			case 'archived':
				$this->job_skills 	= 	$job->getSkills(1); 
				$this->job_tab 		= 	"proposal";

				if($subrequest !== "archived"){
					$archived_users  =   Archive::where('job_id', $job->id)	
											->where('status', 1)
											->get();
					$archived_users_array = array();
					foreach($archived_users as $archived_user){
						$archived_users_array[] = $archived_user->user_id;
					}
				} 
				if($subrequest == "applicants"){
					//archived 
					$freelancers 	=  	User::select("users.*", "proposals.coverletter", "proposals.proposal_amount")	
											->join('proposals', 'proposals.user_id', 'users.id')
											->where('proposals.job_id', $job->id)
											->where('proposals.status', '<=', 2)
											->whereNotIn('users.id', $archived_users_array)
											->paginate(20);
					$this->freelancers = $freelancers;
				} 
				if($subrequest == "shortlisted"){
					$freelancers =  User::select("users.*", "proposals.coverletter", "proposals.proposal_amount")	
										->join('proposals', 'proposals.user_id', 'users.id')
										->join('shortlist', 'shortlist.user_id', 'users.id')
										->where('proposals.job_id', $job->id)
										->where('proposals.status', '<=', 2)
										->where('shortlist.status', '1')
										->whereNotIn('users.id', $archived_users_array)
										->paginate(20);
					$this->freelancers = $freelancers;
				} 
				if($subrequest == "messaged"){ 
					$freelancers =  User::select("users.*", "proposals.coverletter", "proposals.proposal_amount")	
										->join('proposals', 'proposals.user_id', 'users.id')
										->join('message_list', 'message_list.to_user_id', 'users.id')
										->where('proposals.job_id', $job->id)
										->where('message_list.job_id', $job->id)
										->where('message_list.status', 0)
										->whereNotIn('users.id', $archived_users_array)
										->paginate(20); 
					$this->freelancers = $freelancers;
				}
				if($subrequest == "archived"){
					//archived
					$archived 				= 	array();
					$freelancers_archived 	=   User::select("users.*", "proposals.coverletter", "proposals.proposal_amount")	
													->join('proposals', 'proposals.user_id', 'users.id')
													->join('archieved', 'archieved.user_id', 'users.id')
													->where('proposals.job_id', $job->id)
													->where('proposals.status', '<=', 2)
													->where('archieved.status', '1')
													->get(); 
					$freelancers_declined 	=	User::select("users.*", "proposals.coverletter", "proposals.proposal_amount")	
													->join('proposals', 'proposals.user_id', 'users.id')
													->join('declines', 'declines.decline_user', 'users.id')
													->where('proposals.job_id', $job->id)
													->where('proposals.status', 3)
													->where('declines.decline_type', '<>' ,'invite')
													->get(); 
					//declined 
					$this->freelancers_archived   = $freelancers_archived;
					$this->freelancers_declined   = $freelancers_declined;
				}
				return view('employer/jobs/proposals',$this->data);
				break;
			case 'offers':
			case 'hired':
				$this->job_tab 		= 	"hired";
				if($subrequest == "offers"){ 
					$freelancers 		=  	User::select("users.*", "offers.work_details", "offers.serial as offer_serial")	
												->join('offers', 'offers.user_id', 'users.id')
												->where('offers.job_id', $job->id)
												->where(function ($query){
													$query->where('offers.status',      0) 
														->orWhere('offers.status',  3);
												})
												->paginate(10);
					$this->freelancers 	= 	$freelancers;  
				}   
				if($subrequest == "hired"){ 
					$freelancers 		=  	User::select("users.*", "offers.work_details")	
												->join('offers', 'offers.user_id', 'users.id')
												->where('offers.job_id', $job->id)
												->where('offers.status', '<>' , 0)
												->where('offers.status', '<>' , 3)
												->paginate(10);
					$this->freelancers 	= 	$freelancers; 
				} 

				return view('employer/jobs/hired',$this->data); 
				break;
		}
	} 
	public function mainaction_user(Request $request, $job_id, $user_id){ 
		$job 	=	  Job::where("serial", $job_id)
						->where('user_id', Auth::user()->id)
						->first(); 
		if(!isset($job))
			abort(404);
		$this->job = $job; 
		$freelancer =  	User::where('serial', $user_id)
							->first();  
		if(!isset($freelancer))
			return redirect()->route('employer.jobs.mainaction', [$job->serial, 'applicants']); 
		// check user is in porposal offer, invite
		$offer 	   =	Offer::where('job_id', $job->id)
							->where('user_id', $freelancer->id)
							->first();
		if(isset($offer)){
			$freelancer->work_status = "offer";
			$freelancer->offer 	 	 = $offer;
		}
		else{
			$proposal 	= 	Proposal::where('job_id', $job->id)
									->where('user_id', $freelancer->id)
									->first();
			if(isset($proposal)){
				$freelancer->work_status = "proposal";
				$freelancer->proposal 	 = $proposal;
			}
			else{
				$invite 	= 	Invite::where('job_id', $job->id)
									->where('user_id', $freelancer->id)
									->where('status', 0)
									->first();
				
				if(isset($invite)){
					$freelancer->work_status = "invite";
					$freelancer->invite 	 = $invite;
				}
			}	
		} 
		$this->freelancer = $freelancer;
		return view('employer/jobs/userdetails',$this->data); 
	}
	// save freelancer action
	public function addsavefreelancer(Request $request){ 
		$validator = Validator::make($request->all(),  [  
			'user_id'     => 'required',
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200); 
		$user 	= 	User::where('serial', $request->user_id)
						->where('role', 1)
						->first();
		if(!isset($user))
			return response()->json(['status' => 0, 'msg' => "Cannot find this user."], 200); 
		SavedFreelancer::updateOrCreate([
			'user_id' 	=> $user->id,
			'client_id'	=> Auth::user()->id
		],[
			'status'   => 1,
			'notes'    => $request->notes
		]); 
		return response()->json(['status' => 1, 'msg' => "success."], 200);
	}
	public function getsavefreelancer(Request $request){
		$validator = Validator::make($request->all(),  [  
			'user_id'     => 'required',
		]); 
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		
		$user 	= 	User::where('serial', $request->user_id)
						->where('role', 1)
						->first();
		if(!isset($user))
			return response()->json(['status' => 0, 'msg' => "Cannot find this user."], 200);
		$this->user = $user;
		$this->saved_freelancer =  	SavedFreelancer::where('user_id', $user->id)
										->where('status', 1)
										->where('client_id', Auth::user()->id)
										->first(); 
		$html   =	view('employer.jobs.partial.save_freelancer', $this->data)->render();
		return response()->json(array('status' => 1, 'msg' => 'success', 'html' => $html)); 
	}
	public function removesavefreelancer(Request $request){
		$validator = Validator::make($request->all(),  [  
			'user_id'     => 'required',
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200); 
		$user 	= 	User::where('serial', $request->user_id)
						->where('role', 1)
						->first();
		if(!isset($user))
			return response()->json(['status' => 0, 'msg' => "Cannot find this user."], 200); 
		SavedFreelancer::updateOrCreate([
			'user_id' 	=> $user->id,
			'client_id'	=> Auth::user()->id
		],[
			'status'   => 0
		]); 
		return response()->json(['status' => 1, 'msg' => "success."], 200); 
	}
	/************************** ShortList Action **************************/
	public function shortlistaction(Request $request, $job_id){
		$job 	=	Job::where("serial", $job_id)
						->where('user_id', Auth::user()->id)
						->first();
		if(!isset($job))
			return response()->json(['status' => 0, 'msg' => "cannot find this job."], 200);

		$validator = Validator::make($request->all(),  [  
			'user_id'     	=> 'required',
			'request_type'  => 'required'
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200); 
		$user 	= 	User::where('serial', $request->user_id)
						->where('role', 1)
						->first();
		if(!isset($user))
			return response()->json(['status' => 0, 'msg' => "Cannot find this user."], 200);
		
		$request_type = 0;
		if($request->request_type == "add")
			$request_type = 1; 
		Shortlist::updateOrCreate([
			'user_id' 	=> $user->id,
			'job_id'	=> $job->id
		],[
			'status'   => $request_type
		]);  
		return response()->json(['status' => 1, 'msg' => "success."], 200); 
	} 
	public function archiveaction(Request $request, $job_id){
		$job 	=	Job::where("serial", $job_id)
						->where('user_id', Auth::user()->id)
						->first(); 
		if(!isset($job))
			return response()->json(['status' => 0, 'msg' => "cannot find this job."], 200);

		$validator = Validator::make($request->all(),  [  
			'user_id'     	=> 'required',
			'request_type'  => 'required'
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200); 
		$user 	= 	User::where('serial', $request->user_id)
						->where('role', 1)
						->first();
		if(!isset($user))
			return response()->json(['status' => 0, 'msg' => "Cannot find this user."], 200);
		
		$request_type = 0;
		if($request->request_type == "add")
			$request_type = 1; 
		
		Archive::updateOrCreate([
			'user_id' 	=> $user->id,
			'job_id'	=> $job->id
		],[
			'status'   => $request_type
		]);
		return response()->json(['status' => 1, 'msg' => "success."], 200); 
	} 
	public function invitefreelancer(Request $request, $job_id){
		$job 	=	Job::where("serial", $job_id)
						->where('user_id', Auth::user()->id)
						->first();
		if(!isset($job))
			return response()->json(['status' => 0, 'msg' => "cannot find this job."], 200); 
		$validator = Validator::make($request->all(),  [
			'user_id'     => 'required',
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200); 
		$user 	= 	User::where('serial', $request->user_id)
						->where('role', 1)
						->first();
		if(!isset($user))
			return response()->json(['status' => 0, 'msg' => "Cannot find this user."], 200); 
		$is_invited = $user->checkInvited($job->id);
		if($is_invited)
			return response()->json(['status' => 0, 'msg' => "This usser is invited already."], 200);
		$template 	=  	view('employer.jobs.partial.invite_template', ['user' => Auth::user()])->render(); 
		$invitation  	= 	Invite::create([
								'user_id' => $user->id,
								'job_id'  => $job->id,
								'status'  => 0,
								'notes'   => $template
							]);
		// notfication               
		Notification::create([
			'notifications_fromuser' 	=>  Auth::user()->id,
			'notifications_touser'		=>  $user->id,
			'notifications_value'		=>  'You have received an invitation to interview for the job "' . $job->headline . '".',
			'notification_ref'          =>  $invitation->serial,
			'notifications_type'		=> 'invtiation_sent'
		]);
		return response()->json(['status' => 1, 'msg' => "success."], 200);
	}	 
	public function declineaction(Request $request, $request_type, $job_id){  
		$suggested_url = ['invite', 'proposal', 'offer']; 
		if(!in_array( $request_type, $suggested_url)){
			return response()->json(['status' => 0, 'msg' => "invalid request"], 200);
		} 
		$job 	=	Job::where("serial", $job_id)
						->where('user_id', Auth::user()->id)
						->first();
		if(!isset($job))
			return response()->json(['status' => 0, 'msg' => "cannot find this job."], 200);
		$validator = Validator::make($request->all(),  [
			'reason'     	=> 'required',
			'user_id'     	=> 'required'
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		$user 	= 	User::where('serial', $request->user_id)
							->where('role', 1)
							->first();
		if(!isset($user))
			return response()->json(['status' => 0, 'msg' => "Cannot find this user."], 200);
		$reason = DB::table("decline_reasons")->where('type', "proposal_client")->where('id', $request->reason)->first();    
		if(!isset($reason))
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200); 
		if($reason->more_info == 1){
			$validator = Validator::make($request->all(),  [
				'other_reason'     	=> 'required'
			]);
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);  
		} 
		switch($request_type){
			case 'invite':
				$invite = 	Invite::where('job_id', $job->id)
								->where('user_id', $user->id) 
								->first(); 
				if(!isset($invite)){
					return response()->json(['status' => 0, 'msg' => "Invalid request"], 200);
				}
				if($invite->status != 0){
					return response()->json(['status' => 1 ,'msg' => "Invalid request"], 200);
				} 
				Decline::updateOrCreate([
					'job_id' 			=> $invite->job_id,
					'user_id'			=> Auth::user()->id,
					'decline_type' 	 	=> 'invite',
					'decline_user' 	 	=> $user->id,
				],[
					'decline_reference' => $invite->serial,
					'decline_reason' 	=> $request->reason, 
					'decline_note' 	 	=> $request->decline_notes,
					'other_reason' 	 	=> $request->other_reason
				]); 
				$invite->update([
					'status' => 2
				]);
				$notification_message 	= 	"The invitation for '" . $job->headline ."' is withdrawn by client";
				$notification_ref 		=	$invite->serial;
				//Notification 
				Notification::create([
					'notifications_fromuser' 	=>   Auth::user()->id,
					'notifications_touser'		=>   $user->id,
					'notifications_value'		=>   $notification_message,
					'notification_ref'          =>   $notification_ref,
					'notifications_type'		=>   $request_type. '_decline_client_sent'
				]);
				return response()->json(['status' => 1, 'url' => route('employer.jobs.mainaction', [ $job->serial ,'suggested'])  ,'msg' => "success"], 200);
				break;
			case 'proposal':
				$proposal =     Proposal::where('job_id', $job->id)
									->where('user_id', $user->id) 
									->first();
				if(!isset($proposal)){
					return response()->json(['status' => 0, 'msg' => "Invalid request"], 200);
				}
				if($proposal->status >= 3){
					return response()->json(['status' => 0 ,'msg' => "This proposal is widthdrawn already."], 200);
				}
				Decline::updateOrCreate([
					'job_id' 			=> $proposal->job_id,
					'user_id'			=> Auth::user()->id,
					'decline_type' 	 	=> 'proposal',
					'decline_user' 	 	=> $user->id,
				],[
					'decline_reference' => $proposal->serial,
					'decline_reason' 	=> $request->reason, 
					'decline_note' 	 	=> $request->decline_notes,
					'other_reason' 	 	=> $request->other_reason
				]); 
				$proposal->update([
					'status' => 3
				]);
				$notification_message 	= 	"The proposal for '" . $job->headline ."' is declined";
				$notification_ref 		=	$proposal->serial;
				//Notification 
				Notification::create([
					'notifications_fromuser' 	=>   Auth::user()->id,
					'notifications_touser'		=>   $user->id,
					'notifications_value'		=>   $notification_message,
					'notification_ref'          =>   $notification_ref,
					'notifications_type'		=>   $request_type. '_decline_client_sent'
				]);
				return response()->json(['status' => 1, 'url' =>  route('employer.jobs.mainaction', [ $job->serial ,'applicants'])  ,'msg' => "success"], 200);
				break;
			case 'offer':
				$offer 	=	Offer::where('job_id', $job->id)
								->where('user_id', $user->id)
								->first();
				if(!isset($offer)){
					return response()->json(['status' => 0, 'msg' => "Invalid request"], 200);
				}

				if($offer->status != 0){
					return response()->json(['status' => 0 ,'msg' => "This offer is declined or hired already."], 200);
				}
				// refund the money to the client
				Decline::updateOrCreate([
					'job_id' 			=> $offer->job_id,
					'user_id'			=> Auth::user()->id,
					'decline_type' 	 	=> 'offer',
					'decline_user' 	 	=> $user->id,
				],[
					'decline_reference' => $offer->serial,
					'decline_reason' 	=> $request->reason, 
					'decline_note' 	 	=> $request->decline_notes,
					'other_reason' 	 	=> $request->other_reason
				]);  
				$offer->update([
					'status' => 3 
				]);
				$notification_message 	= 	"The offer for '" . $job->headline ."' is withdrawn";
				$notification_ref 		=	$offer->serial;
				//Notification 
				Notification::create([
					'notifications_fromuser' 	=>   Auth::user()->id,
					'notifications_touser'		=>   $user->id,
					'notifications_value'		=>   $notification_message,
					'notification_ref'          =>   $notification_ref,
					'notifications_type'		=>   $request_type. '_decline_client_sent'
				]);
				return response()->json(['status' => 1, 'url' =>  route('employer.jobs.mainaction', [ $job->serial ,'applicants'])  ,'msg' => "success"], 200);
				break;
		} 
		return response()->json(['status' => 0, 'msg' => "invalid request"], 200); 
	}
	/***************************** Offer Action ******************************/
	public function sendoffer(Request $request, $job_id, $user_id){
		$job 				=	Job::where("serial", $job_id)
									->where('user_id', Auth::user()->id)
									->first();
		if(!isset($job)){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "This job is not exist."], 200); 
			else
				abort(404);
		} 
		$this->job 			= 	$job;
		$freelancer 		=  	User::where('serial', $user_id)
									->first(); 
		if(!isset($freelancer)){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "This user is not exist."], 200); 
			else
				abort(404);
		} 
		//check offer is exist
		$offer 	= 	Offer::where('job_id', $job->id)
						->where('user_id', $freelancer->id)
						->first();
		if(isset($offer)){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "The offer is exist already."], 200); 
			else
				return redirect()->route('employer.jobs.mainaction', [$job->serial ,'offers']);
		}
		//check decline 
		$decline = $freelancer->getDecline($job->id);
		if(isset($decline)){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "The offer is declined already."], 200); 
			else
				return redirect()->route('employer.jobs.mainaction', [$job->serial ,'archived']);
		} 
		$this->freelancer 	= 	$freelancer;
		if($request->isMethod('post')){
			if($job->payment_type == "hourly"){
				$validator = Validator::make($request->all(),  [
					'offer_amount'     => 'required | numeric',
					'weekly_limit'     => 'required | numeric',
					'minimum_hours'    => 'required | numeric',
					'contract_title'   => 'required | max: 512',
					'work_details'     => 'required | max: 5000', 
				]);
			}
			else{
				$validator = Validator::make($request->all(),  [  
					'offer_amount'     => 'required | numeric',
					'contract_title'   => 'required | max: 512',
					'work_details'     => 'required | max: 5000', 
				]);
			} 
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
			
			if($request->offer_amount <= 0)
				return response()->json(['status' => 0, 'msg' => "The amount should be greter than 0."], 200);
 
			//if fixed then we will check he can pay 
			if($job->payment_type == "fixed"){ 
				$job_poster_fee =  MasterSetting::getValue('job_poster_fee');
				$escrow_fee     =  100 - $job_poster_fee;

				$offer_amount =  round($request->offer_amount * 100 / $escrow_fee, 2); 
				$service_fee  =  round($offer_amount -  $request->offer_amount, 2); 
				$card 	= 	Card::where('serial', $request->card)
								->where('user_id', Auth::user()->id)
								->where('status', 'verified')
								->where('primary_method', 1)
								->first();
				
				if(!isset($card))
					return response()->json(array('status' => 0, 'msg' =>  "This card is not exist or not verified yet."), 200); 
				// transaction,  escrow
				$milestone_index 	=	Milestone::GenerateSerial();
				$sendData 			= 	array();
				$sendData['refId']	= 	$milestone_index; 
				$pay_result   		=   $card->payOrderWithProfile($offer_amount, $sendData);

				if($pay_result['status']){
					$transaction	 = Transaction::create([
						'amount'        =>  $offer_amount,
						'status'        => 'success',
						'type'          => 'charge',
						'card_id'       =>  $card->id,
						'user_id'       =>  Auth::user()->id,
						'transaction'   =>  $pay_result['transId'],
						'authCode'      =>  $pay_result['authCode'], 
						'avsResultCode' =>  $pay_result['avsResultCode'],
						'cvvResultCode' =>  $pay_result['cvvResultCode'],
						'accountType'   =>  $pay_result['accountType'],
						'accountNumber' =>  $pay_result['accountNumber']
					]);
					$payment_result           =  Card::getResponsecodeResult($pay_result['responseCode']); 
					if(!$payment_result['status']){
						$error_msg = "There is an error with processing payments right now. <br>  Please try again later."; 
						return response()->json(array('status' => 0, 'msg' =>  $error_msg), 200);
					}
				}
				else{ 
					$error_msg = "There was a system problem.  Please retry in a few minutes.";  
					return response()->json(array('status' => 0, 'msg' => $error_msg), 200);
				}
			} 
			$offer 	= 	Offer::create([
							'job_id' 		=> $job->id,
							'payment_type'	=> $job->payment_type,
							'amount'        => $request->offer_amount,
							'user_id'		=> $freelancer->id,
							'employer_id'   => Auth::user()->id,
							'work_details'  => $request->work_details,
							'offer_date'	=> $job->job_date,
							'offer_time'	=> $job->job_time,
							'contract_title'=> $request->contract_title,
							'weekly_limit'	=> $request->weekly_limit,
							'minimum_hours'	=> $request->minimum_hours,
							'start_time'    => date("Y-m-d H:i:s", strtotime("+7 days"))
						]); 
			if($offer->payment_type == "fixed"){
				// create milestone
				$milestone = Milestone::create([
					'serial'		=> $milestone_index,
					'offer_id' 		=> $offer->id,
					'amount'   		=> $offer->amount,
					'deposit_status'=> 1,
					'status'		=> 'active',
					'headline'		=> $job->headline,
					'start_date'	=> date("Y-m-d H:i:s")
				]);
				//charge amount
				$escrow 	= 	Escrow::create([
									'amount' 	 => $offer_amount,
									'ref_id' 	 => $transaction->id,
									'offer_id'	 => $offer->id,
									'status'	 => 'available',
									'type'	 	 => "Payment",
									'description'=> "Paid from " . $card->card_type . ' for Milestone ' . ($milestone->milestone_sort + 1) . ' ' . $milestone->headline, 
									'direction'	 => 'in',
									'user_id'	 => Auth::user()->id
								]);
				// remove service fee
				$escrow 	= 	Escrow::create([
									'amount' 	 => $service_fee,
									'ref_id' 	 => $transaction->id,
									'offer_id'	 => $offer->id,
									'status'	 => 'available',
									'type'	 	 => "Processing Fee",
									'description'=> "Payment Processing Fee", 
									'direction'	 => 'out',
									'user_id'	 => Auth::user()->id
								]);
			}
			// check message list is exist
			$message_list 		= 	MessageList::where('job_id', $job->id)
										->where('to_user_id', $freelancer->id)
										->first(); 
			if(!isset($message_list))
				$message_list 	=  MessageList::addMessageList(Auth::user()->id, $freelancer->id, $job->id);  
			$this->offer 		=  $offer;
			$message_content 	=  view('messages.partial.offer_template', $this->data)->render();  
			Message::addMessage(Auth::user()->id, $freelancer->id, $message_list->id, $message_content, 3, $offer->created_at, $offer->id); 
			//Notification 
			Notification::create([
				'notifications_fromuser' 	=>  Auth::user()->id,
				'notifications_touser'		=>  $freelancer->id,
				'notifications_value'		=>  'You have received the offer from ' .  Auth::user()->accounts->name,
				'notification_ref'          =>  $offer->serial,
				'notifications_type'		=> 'offer_sent'
			]);
			$proposal = $job->getProposal($freelancer->id);
			if(isset($proposal)){
				$proposal->update([
					'status' => 10
				]);
			}
			$url = route('employer.jobs.morehiring', $offer->serial); 
			return response()->json(['status' => 1, 'url' => $url , 'msg' => "success."], 200);
		}
		return view('employer.jobs.offer', $this->data);
	}
	public function createoffer(Request $request, $user_id){
		$freelancer 		=  	User::where('serial', $user_id)
									->first(); 
		if(!isset($freelancer)){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "This user is not exist."], 200); 
			else
				abort(404);
		}
		if($request->has('job_id')){
			$pref_job 		=	Job::where("serial", $request->job_id)
									->where('user_id', Auth::user()->id)
									->first(); 
			if(isset($pref_job)){
				$this->pref_job = $pref_job;  
			}
		}						
		$this->freelancer 	= 	$freelancer;
		if($request->isMethod('post')){
			$payment_type = "hourly"; 
			if($request->related_job_posting){
				$job 		=	Job::where("serial", $request->related_job_posting)
										->where('user_id', Auth::user()->id)
										->first(); 
				if(!isset($job))
					return response()->json(['status' => 0, 'msg' => "This job is not exist."], 200);
				$payment_type 	= 	$job->payment_type;
			}
			if($payment_type == "hourly"){
				$validator = Validator::make($request->all(),  [
					'offer_amount'     => 'required | numeric',
					'weekly_limit'     => 'required | numeric',
					'minimum_hours'    => 'required | numeric',
					'contract_title'   => 'required | max: 512',
					'work_details'     => 'required | max: 5000', 
				]);
			}
			else{
				$validator = Validator::make($request->all(),  [  
					'offer_amount'     => 'required | numeric',
					'contract_title'   => 'required | max: 512',
					'work_details'     => 'required | max: 5000', 
				]);
			} 
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);

			if($request->offer_amount <= 0)
				return response()->json(['status' => 0, 'msg' => "The amount should be greter than 0."], 200);
 
			//if fixed then we will check he can pay 
			if($payment_type == "fixed"){ 
				$job_poster_fee =  MasterSetting::getValue('job_poster_fee');
				$escrow_fee     =  100 - $job_poster_fee; 
				$offer_amount 	=  round($request->offer_amount * 100 / $escrow_fee, 2); 
				$service_fee  	=  round($offer_amount -  $request->offer_amount, 2); 
				$card 			= 	Card::where('serial', $request->card)
										->where('user_id', Auth::user()->id)
										->where('status', 'verified')
										->where('primary_method', 1)
										->first(); 
				if(!isset($card))
					return response()->json(array('status' => 0, 'msg' =>  "This card is not exist or not verified yet."), 200); 
				// transaction,  escrow
				$milestone_index 	=	Milestone::GenerateSerial();
				$sendData 			= 	array();
				$sendData['refId']	= 	$milestone_index; 
				$pay_result   		=   $card->payOrderWithProfile($offer_amount, $sendData);

				if($pay_result['status']){
					$transaction	 = Transaction::create([
						'amount'        =>  $offer_amount,
						'status'        => 'success',
						'type'          => 'charge',
						'card_id'       =>  $card->id,
						'user_id'       =>  Auth::user()->id,
						'transaction'   =>  $pay_result['transId'],
						'authCode'      =>  $pay_result['authCode'], 
						'avsResultCode' =>  $pay_result['avsResultCode'],
						'cvvResultCode' =>  $pay_result['cvvResultCode'],
						'accountType'   =>  $pay_result['accountType'],
						'accountNumber' =>  $pay_result['accountNumber']
					]);
					$payment_result           =  Card::getResponsecodeResult($pay_result['responseCode']); 
					if(!$payment_result['status']){
						$error_msg = "There is an error with processing payments right now. <br>  Please try again later."; 
						return response()->json(array('status' => 0, 'msg' =>  $error_msg), 200);
					}
				}
				else{
					$error_msg = "There was a system problem.  Please retry in a few minutes.";  
					return response()->json(array('status' => 0, 'msg' => $error_msg), 200);
				}
			}

			$job_id 	= null;
			$job_date 	= null;
			$job_time   = null;

			if(isset($job)){
				$job_id 	= 	$job->id;
				$job_date 	=	$job->job_date;
				$job_time 	=	$job->job_time;
			}

			$expire_offer_date 	=  	MasterSetting::getValue('expire_offer_date');

			$offer 	= 	Offer::create([
				'job_id' 		=> $job_id,
				'payment_type'	=> $payment_type,
				'amount'        => $request->offer_amount,
				'user_id'		=> $freelancer->id,
				'employer_id'   => Auth::user()->id,
				'work_details'  => $request->work_details,
				'offer_date'	=> $job_date,
				'offer_time'	=> $job_time,
				'contract_title'=> $request->contract_title,
				'weekly_limit'	=> $request->weekly_limit,
				'minimum_hours'	=> $request->minimum_hours,
				'start_time'    => date("Y-m-d H:i:s", strtotime("+" . $expire_offer_date . " days"))
			]);

			if($offer->payment_type == "fixed"){
				$milestone = Milestone::create([
					'serial'		=> $milestone_index,
					'offer_id' 		=> $offer->id,
					'amount'   		=> $offer->amount,
					'deposit_status'=> 1,
					'status'		=> 'active',
					'headline'		=> $job->headline,
					'start_date'	=> date("Y-m-d H:i:s")
				]);
				//charge amount
				$escrow 	= 	Escrow::create([
									'amount' 	 => $offer_amount,
									'ref_id' 	 => $transaction->id,
									'offer_id'	 => $offer->id,
									'status'	 => 'available',
									'type'	 	 => "Payment",
									'description'=> "Paid from " . $card->card_type . ' for Milestone ' . ($milestone->milestone_sort + 1) . ' ' . $milestone->headline, 
									'direction'	 => 'in',
									'user_id'	 => Auth::user()->id
								]);
				// remove service fee
				$escrow 	= 	Escrow::create([
									'amount' 	 => $service_fee,
									'ref_id' 	 => $transaction->id,
									'offer_id'	 => $offer->id,
									'status'	 => 'available',
									'type'	 	 => "Processing Fee",
									'description'=> "Payment Processing Fee", 
									'direction'	 => 'out',
									'user_id'	 => Auth::user()->id
								]);
			}
			
			// check message list is exist
			if(isset($job)){
				$message_list 		= 	MessageList::where('job_id', $job->id)
											->where('to_user_id', $freelancer->id)
											->where('type', 'job')
											->first(); 
				if(!isset($message_list))
					$message_list 	=  MessageList::addMessageList(Auth::user()->id, $freelancer->id, $job->id);  	
			}else{
				$message_list 		= 	MessageList::where('job_id', $offer->id)
											->where('to_user_id', $freelancer->id)
											->where('type', 'offer')
											->first(); 
				if(!isset($message_list))
					$message_list 	=  MessageList::addMessageList(Auth::user()->id, $freelancer->id, $offer->id, 'offer');
			}
			$this->offer 		=  $offer;
			$message_content 	=  view('messages.partial.offer_template', $this->data)->render();  
			Message::addMessage(Auth::user()->id, $freelancer->id, $message_list->id, $message_content, 3, $offer->created_at, $offer->id);
			Notification::create([
				'notifications_fromuser' 	=>  Auth::user()->id,
				'notifications_touser'		=>  $freelancer->id,
				'notifications_value'		=>  'You have received the offer from ' .  Auth::user()->accounts->name,
				'notification_ref'          =>  $offer->serial,
				'notifications_type'		=>  'offer_sent'
			]);
			if(isset($job)){
				$proposal = $job->getProposal($freelancer->id);
				if(isset($proposal)){
					$proposal->update([
						'status' => 10
					]);
				}	
			}

			if(isset($job))
				$url = route('employer.jobs.morehiring', $offer->serial);
			else{
				$url = route('employer.contracts');
			} 
			return response()->json(['status' => 1, 'url' => $url , 'msg' => "success."], 200); 
		} 
		// get jobs that has no offer
		$offers 	= 	Offer::where('user_id', $freelancer->id)
							->select('job_id')
							->get(); 
		$hired_jobs = 	array();
		foreach($offers as $offer){
			$hired_jobs[] = $offer->job_id;
		}
		$jobs		=  	Job::where('status', 1)
							->whereNotIn('id', $hired_jobs)
							->where('user_id', Auth::user()->id)
							->get();
		$this->jobs = 	$jobs;
		return view('employer.jobs.create_offer', $this->data);
	}
	public function getofferdetails(Request $request, $user_id){
		$freelancer 		=  	User::where('serial', $user_id)
									->first(); 
		if(!isset($freelancer))
			return response()->json(['status' => 0, 'msg' => "This user is not exist."], 200); 
		
		$validator = Validator::make($request->all(), [
			'job_id'   => 'required | max: 512'
		]);
		if($request->job_id != ""){
			$job	=  	Job::where('status', 1)
							->where('user_id', Auth::user()->id)
							->where('serial', $request->job_id)
							->first();
		}                                                                                                            
		$send_data	 					= array();
		$send_data['status']  			= 1;
		$send_data['msg']  				= "success";

		$template_data  					= 	array();
		$template_data['freelancer'] 		=   $freelancer;

		if(isset($job)){
			$send_data['contract_title']	= 	$job->headline;
			$template_data['job']			=	$job;
		}
		else{
			$send_data['contract_title']	= "";
		} 
		$send_data['html'] = view('employer.jobs.partial.job_offer_terms', $template_data)->render(); 
		return response()->json($send_data, 200); 
	} 
	public function offerdetails(Request $request, $offer_id){ 
		$offer  = 	Offer::where('serial', $offer_id)
						->where('employer_id', Auth::user()->id)
						->first();
		if(!isset($offer))
			abort(404);
		$this->offer = $offer;
		return view('employer.jobs.offerdetail', $this->data);   
	}
	public function editoffer(Request $request, $offer_id){ 
		$offer  = 	Offer::where('serial', $offer_id)
						->where('employer_id', Auth::user()->id)
						->first();
		if(!isset($offer)){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "The offer is not exist."], 200); 
			else
				abort(404);
		}
		if($offer->status !== 0){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "The offer is closed or expired."], 200); 
			else
				return redirect()->route('employer.jobs')->with('error', 'The offer is closed or expired.');
		}

		if($request->isMethod('post')){  
			if($offer->payment_type == "hourly"){
				$validator = Validator::make($request->all(),  [
					'offer_amount'     => 'required | numeric',
					'weekly_limit'     => 'required | numeric',
					'contract_title'   => 'required | max: 512',
					'work_details'     => 'required | max: 5000', 
				]);
			}
			else{
				$validator = Validator::make($request->all(),  [  
					'offer_amount'     => 'required | numeric',
					'contract_title'   => 'required | max: 512',
					'work_details'     => 'required | max: 5000', 
				]);
			}
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);		 
			$offer->update([  
							'amount'        => $request->offer_amount, 
							'work_details'  => $request->work_details,
						//	'offer_date'	=> $job->job_date,
						//	'offer_time'	=> $job->job_time,
							'contract_title'=> $request->contract_title,
							'weekly_limit'	=> $request->weekly_limit
						]);  
			// check message list is exist
			$message_list 		= 	MessageList::where('job_id', $offer->job_id)
										->where('to_user_id', $offer->user_id)
										->first(); 
			if(!isset($message_list))
				$message_list 	=  MessageList::addMessageList(Auth::user()->id, $freelancer->id, $offer->job_id);  
			$this->offer 		=  $offer;  
			/*
			$message_content 	=  view('messages.partial.offer_template', $this->data)->render(); 
			Message::addMessage(Auth::user()->id, $freelancer->id, $message_list->id, $message_content);
			$proposal = $job->getProposal($freelancer->id);
			if(isset($proposal)){
				$proposal->update([
					'status' => 10
				]);
			} 
			*/ 
			$url = route('employer.jobs.viewoffer', $offer->serial);
			return response()->json(['status' => 1, 'url' => $url , 'msg' => "success."], 200); 
		}

		$this->offer = $offer; 
		return view('employer.jobs.offer', $this->data);
	} 
	public function morehiring(Request $request, $offer_id){
		$offer 	=	Offer::where("serial", $offer_id)
						->where('employer_id', Auth::user()->id)
						->first(); 
		if(!isset($offer))
			abort(404);
		if($offer->status != 0){ 
			return redirect()->route('employer.jobs')->with('error', "This offer is not passed already.");
		}

		if($request->isMethod('post')){
			$validator = Validator::make($request->all(), [
				'done_hiring'   => 'required | max: 512'
			]); 
			if($validator->fails())
				return redirect()->back(); 
			if( ($request->done_hiring !== "done") && ($request->done_hiring !== "more")){
				return redirect()->back();	
			} 
			if($request->done_hiring == "done"){
				$hire_more = 0;
			} 
			if($request->done_hiring == "more"){
				$hire_more = 1;
			}
			$job = $offer->getJob(); 
			if(isset($job)){
				$job->update([
					'hire_more' => $hire_more
				]);
			}
			return redirect()->route('employer.jobs.mainaction', [ $job->serial, 'applicants']);
		}

		$this->offer		 =  $offer;
		return view('employer.jobs.hire_more', $this->data); 
	}
}