<?php
namespace App\Http\Controllers\Employee\Profile;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Session, Storage;
use Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Model\Education;
use App\Model\Certification;
use App\Model\Media;
use App\Model\Verification;
use App\Model\UsersAccount;
use App\Model\Skill;
use App\Model\RoleSkill;
use App\Model\SlackInfo;
use App\Model\JobHistory;
use App\Model\Role; 
use App\Model\Notification;
use Carbon\Carbon;
use App\Model\DriverLicense;
use App\Model\Industry;
use App\Model\SubCategory;
use App\Http\Controllers\BaseController;
class EmployeesprofileController extends BaseController{ 
    public function __construct(){
        $this->middleware(array('auth','employee'));
    }
    public function index(){
		$user 						= 	Auth::user();
		$this->user 				= 	User::find($user->id);
		$this->skills   			= 	Skill::where('deleted', 0)->get();
		$this->verification 		= 	Verification::where('user_id', $user->id)->where('type', 'address')->where('status', 'new')->get();
		$this->rverification 		= 	Verification::where('user_id', $user->id)->where('type', 'address')->where('status', 'rejected')->get();
		$this->averification 		= 	Verification::where('user_id', $user->id)->where('type', 'address')->where('status', 'verified')->get(); 
		$this->states             	=   DB::table('states')->get();
		
		$this->industries 			= 	Industry::where('deleted', 0)->get();  
        return view('employee/profile/profile', $this->data);
	}
	public function address(Request $request){
        $rules = array(
			'caddress'              => 'required',
			'city'              	=> 'required',
			'state'               	=> 'required',
			'zip'            		=> 'required', 
        ); 
        $input_arr = array(
			'caddress'            => $request->input('caddress'),
			'city'                => $request->input('city'),
			'state'               => $request->input('state'),
			'zip'                 => $request->input('zip'),
			'oaddress'            => $request->input('oaddress')
        );
		$message= array(
			'caddress.required'=>'Please provide your Address.',
			'city.required'=>'Please provide your city.',
			'state.required'=>'Please provide your state.',
			'zip.required'=>'Please provide a valid zip.' 
		);
        $validator = Validator::make($input_arr,$rules,$message); 
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
            //return Redirect::back()->withErrors($validator)->withInput();
        else: 
			if($request->has('profile_start')){
				$rules = array(
					'birthdate'            => 'required',
				);
				$input_arr = array(
					'birthdate'            => $request->input('caddress'),
				);
				$message= array(
					'birthdate.required'=>'Please provide the birthdate.'
				);

				$validator = Validator::make($input_arr,$rules,$message);
				if($validator->fails()):
					return response()->json(['error'=>true,'errors'=>$validator->errors()]);
				endif; 
			}

			$address 	=	$request->zip . ", " . $request->caddress . ", " . $request->city  . "," . $request->state . ",USA"; 
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
			$old_user 			= UsersAccount::find(Auth::user()->id)->toArray();
			$user 				= UsersAccount::find(Auth::user()->id);
			$user->caddress 	= $request->input('caddress');
			$user->city 		= $request->input('city');
			$user->state 		= $request->input('state');
			$user->zip 			= $request->input('zip');
			$user->lat 			= $latitude;
			$user->lng 			= $longitude;
			$user->oaddress 	= $request->input('oaddress');
			if($request->has('profile_start')){
				$birthdate  			= date('Y-m-d', strtotime( $request->input('birthdate') )); 
				$user->birthdate 		= $birthdate;
			}
			$user->save();
			$uh 				= new User(); 
			$uh->Updatehistory($old_user,$input_arr);
			if($request->has('profile_start')){
				$user->start_stage = 1;
				$user->save();
			}
			return response()->json(['error'=>false,'success'=>'Updated successfully!']); 
		endif;  
	}
	public function driverlicense(Request $request){
		$rules = array(
			'driver_state'            => 'required',
			'plate_number'            => 'required',
			'expiration_year'         => 'required',
			'expiration_month'        => 'required', 
        );
        $input_arr = array(
			'driver_state'            => $request->input('driver_state'),
			'plate_number'            => $request->input('plate_number'),
			'expiration_year'         => $request->input('expiration_year'),
			'expiration_month'        => $request->input('expiration_month'), 
        );
		$message= array(
			'driver_state.required'=>'Please provide the state.',
			'plate_number.required'=>'Please provide the number.',
			'expiration_year.required'=>'Please provide the expiration year.',
			'expiration_month.required'=>'Please provide a expiration month.' 
		);
        $validator = Validator::make($input_arr,$rules,$message); 
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else: 
			DriverLicense::updateOrCreate([
				'user_id' 			=> Auth::user()->id
			],[
				'plate_number' 		=> strtoupper($request->plate_number),
				'state'				=> $request->driver_state,
				'expiration_year'	=> $request->expiration_year,
				'expiration_month'	=> $request->expiration_month,
			]); 
			return response()->json(['error'=>false,'success'=>'Updated successfully!']); 
		endif; 
	}   
	/******************************************* Job History **********************************************/
	public function addjobhistory(Request $request){
		$rules = array(
			'job_title'              => 'required | max:512',
			'job_company'            => 'required | max:512'
        );
        $input_arr = array(
			'job_title'              => $request->input('job_title'),
			'job_company'            => $request->input('job_company')
        ); 
		$message= array(
			'job_title.required'	=>	'Please provide the job title.',
			'job_company.required'	=>	'Please provide the job company.',
		);
		$validator = Validator::make($input_arr,$rules,$message);
		
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:  
			JobHistory::create([
				'job_title' 			=> $request->job_title,
				'job_company' 			=> $request->job_company,
				'job_start_date_year' 	=> $request->job_start_date_year,
				'job_end_date_year' 	=> $request->job_end_date_year,
				'job_start_date_month' 	=> $request->job_start_date_month,
				'job_end_date_month' 	=> $request->job_end_date_month,
				'job_description' 		=> $request->job_description,
				'user_id' 				=> Auth::user()->id
			]);
			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif; 
	}
	public function getjobhistory(Request $request){
		$validator = Validator::make($request->all(),  [  
            'jobhistory'     => 'required',
		]); 
        if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		
		$jobhistory 	= 	JobHistory::where('user_id', Auth::user()->id)
								->where('serial', $request->jobhistory)
								->first();
		if(!isset($jobhistory)){
			return response()->json(['status' => 0, 'msg' => "Unknow educatijob history."], 200);
		} 
		$html =  view("employee.profile.jobhistory_detail", compact('jobhistory'))->render(); 
		return response()->json(array('status' => 1, 'msg' => 'success', 'html' => $html)); 
	}
	public function updatejobhistory(Request $request){
		$rules = array(
			'job_title'              => 'required | max:512',
			'job_company'            => 'required | max:512'
        ); 
		$message= array(
			'job_title'              => $request->input('job_title'),
			'job_company'            => $request->input('job_company')
		); 
        $validator = Validator::make($request->all(),$rules,$message);

        if( $validator->fails() ):  
			return response()->json(['error'=>true, 'errors'=>$validator->errors()]);
        else:
			$jobhistory 	= 	JobHistory::where('user_id', Auth::user()->id)
									->where('serial', $request->jobhistory)
									->first();
			if(!isset($jobhistory)){
				return response()->json(['status' => 0, 'msg' => "Unknow Job history."], 200);
			}
			
			$jobhistory->update([
				'job_title' 			=> $request->job_title,
				'job_company' 			=> $request->job_company,
				'job_start_date_year' 	=> $request->job_start_date_year,
				'job_end_date_year' 	=> $request->job_end_date_year,
				'job_start_date_month' 	=> $request->job_start_date_month,
				'job_end_date_month' 	=> $request->job_end_date_month,
				'job_description' 		=> $request->job_description
			]);

			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif;		
	} 
	public function deletejobhistory(Request $request, $id){
		$jobhistory 	= 	JobHistory::where('user_id', Auth::user()->id)
								->where('serial', $id)
								->first(); 
		if(!isset($jobhistory)){
			abort(404);
		}                           
		$jobhistory->update([
			'is_deleted' => 1
		]);
		return redirect()->back()->with('message', "The job history is removed successfully");
	}
	/******************************************* Education ************************************************/
	public function addeducation(Request $request){
		$rules = array(
			'school'              => 'required'
        );
        $input_arr = array(
			'school'              => $request->input('school')
        ); 
		$message= array(
			'school.required'=>'Please provide your Address.'
		); 
        $validator = Validator::make($input_arr,$rules,$message);

        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:  
			$education = new Education;
			$education->user_id = Auth::user()->id;
			$education->school = $request->input('school');
			$education->degree = $request->input('degree');
			$education->fieldofstudy = $request->input('fieldofstudy');
			$education->startyear = $request->input('startyear');
			$education->endyear = $request->input('endyear');
			$education->grade = $request->input('grade');
			$education->activities = $request->input('activities');
			$education->description = $request->input('description');
			$education->save(); 
			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif;
	}
	public function geteducation(Request $request){
		$validator = Validator::make($request->all(),  [  
            'education'     => 'required',
		]); 
        if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		
		$education 	= 	Education::where('user_id', Auth::user()->id)
								->where('serial', $request->education)
								->first();
		if(!isset($education)){
			return response()->json(['status' => 0, 'msg' => "Unknow education."], 200);
		} 
		$html =  view("employee.profile.education_detail", compact('education'))->render(); 
		return response()->json(array('status' => 1, 'msg' => 'success', 'html' => $html)); 
	}
	public function deleteeducation(Request $request, $id){
		$education 	= 	Education::where('user_id', Auth::user()->id)
							->where('serial', $id)
							->first(); 
		if(!isset($education)){
			abort(404);
		}
		$education->update([
			'is_deleted' => 1
		]);
		return redirect()->back()->with('message', "The education is removed successfully");
	}
	public function updateeducation(Request $request){
		$rules = array(
			'education'		  	=> 'required',
			'school'            => 'required'
        ); 
		$message= array(
			'education.required'=>'Please provide your eduction.',
			'school.required'=>'Please provide your Address.'
		); 
        $validator = Validator::make($request->all(),$rules,$message);

        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:
			$education 	= 	Education::where('user_id', Auth::user()->id)
								->where('serial', $request->education)
								->first();
			if(!isset($education)){
				return response()->json(['status' => 0, 'msg' => "Unknow education."], 200);
			}
			
			$education->school 			= $request->input('school');
			$education->degree 		 	= $request->input('degree');
			$education->fieldofstudy 	= $request->input('fieldofstudy');
			$education->startyear 		= $request->input('startyear');
			$education->endyear 		= $request->input('endyear');
			$education->grade 			= $request->input('grade');
			$education->activities 		= $request->input('activities');
			$education->description 	= $request->input('description');
			$education->save();

			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif;		
	}
	public function misc(Request $request){
        $rules = array(
			'headline'               => 'required',
			'birthdate'              => 'required',
			'industry'               => 'required',
			'socialsecuritynumber'   => 'nullable'
        );
        $input_arr = array(
			'headline'                 => $request->input('headline'),           
			'birthdate'                => $request->input('birthdate'),           
			'industry'                 => $request->input('industry'),
			'socialsecuritynumber'     => $request->input('socialsecuritynumber'),           
        );
		$message= array(
			'headline.required'=>'Please provide your headline.',
			'birthdate.required'=>'Please enter your birthdate.',
			'industry.required'=>'Please select your industry.',
		//	'socialsecuritynumber.required'=>'Please enter your social security number.'
		);
        $validator = Validator::make($input_arr,$rules,$message); 
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:
			$old_user 					= 	UsersAccount::find(Auth::user()->id)->toArray();
			$user 						= 	UsersAccount::find(Auth::user()->id);
			$user->headline 			= 	$request->input('headline'); 
			$birthdate  				= 	date('Y-m-d', strtotime( $request->input('birthdate') )); 
			$user->birthdate 			= 	$birthdate; 
			$user->industry 			= 	$request->input('industry');

			if($request->has('socialsecuritynumber'))
				$user->socialsecuritynumber = encrypt($request->input('socialsecuritynumber'));
			$user->save();
			$uh = new User(); 
			$uh->Updatehistory($old_user,$input_arr); 
			return response()->json(['error'=>false,'success'=>'Updated successfully!']);
		endif;  
    }
	public function socialsecuritynumber(Request $request){ 
		if($request->has('profile_start')){
			$rules = array(
				'socialsecuritynumber' => 'nullable' 
			); 
		}
		else{
			$rules = array(
				'socialsecuritynumber' => 'required' 
			); 
		}
        $input_arr = array(
			'socialsecuritynumber' => $request->input('socialsecuritynumber')          
		);
		$message= array(
			'socialsecuritynumber.required'=>'Please provide your social security number.' 
		);
		$validator = Validator::make($input_arr,$rules,$message);  

        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]); 
        else: 
			$old_user 					= UsersAccount::find(Auth::user()->id)->toArray();
			$user 						= UsersAccount::find(Auth::user()->id);
			if($request->has('socialsecuritynumber')){
				$user->socialsecuritynumber = encrypt($request->input('socialsecuritynumber'));
				$user->save();
				$uh = new User(); 
				$uh->Updatehistory($old_user,$input_arr); 	
			} 
			if($request->has('profile_start')){
				$user->start_stage = 3;
				$user->save();
			}
			return response()->json(['error'=>false,'success'=>'Updated successfully!']);
		endif;
	}  
	/*******************************************  Certification  ************************************************/
	public function addcertification(Request $request){  
		$rules = array(
			'description'          => 'required',
			'picture'              => 'nullable |  mimes:jpeg,png,jpg,gif,svg | max:655366666',
		); 
		$message= array(
			'description.required'	=> 'Please provide certification details.',
			'picture.image'			=> 'Invalid image format',
		); 
		$validator = Validator::make($request->all(),$rules,$message);  

		if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
		else:
			$picture = null;
			if($request->hasFile('picture') ){  
				$extension      = 	$request->file('picture')->getClientOriginalExtension();
				$picture         = 	'certification/' . strtolower( Auth::user()->id . time() . '.'.$extension ); 
				Storage::disk('spaces')->put( $picture, file_get_contents($request->file('picture')->getRealPath()), 'public'); 
			}
			Certification::create([
				'user_id' 		=> Auth::user()->id,
				'picture'		=> $picture,
				'description' 	=> $request->input('description')
			]); 
			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif;
	} 
	public function getcertification(Request $request){
		$validator = Validator::make($request->all(),  [  
            'certication'     => 'required',
		]); 
        if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		
		$certification = 	Certification::where('user_id', Auth::user()->id)
										->where('serial', $request->certication)
										->first();
		if(!isset($certification)){
			return response()->json(['status' => 0, 'msg' => "Unknow certification."], 200);
		} 
		$html =  view("employee.profile.certification_detail", compact('certification'))->render(); 
		return response()->json(array('status' => 1, 'msg' => 'success', 'html' => $html)); 
	} 
	public function updatecertification(Request $request){
		$rules = array(
			'description'          => 'required',
			'certification'		   => 'required',
			'picture'              => 'nullable |  mimes:jpeg,png,jpg,gif,svg | max:655366666',
		); 
		$message= array(
			'description.required'	=> 'Please provide certification details.',
			'picture.image'			=> 'Invalid image format',
		); 
		$validator = Validator::make($request->all(),$rules,$message); 
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
		else:
			$certification = 	Certification::where('user_id', Auth::user()->id)
										->where('serial', $request->certification)
										->first();
			if(!isset($certification)){
				return response()->json(['status' => 0, 'msg' => "Unknow certification."], 200);
			}

			$picture  = $certification->picture;
			if($request->hasFile('picture') ){  
				$extension      = 	$request->file('picture')->getClientOriginalExtension();
				$picture         = 	'certification/' . strtolower( Auth::user()->id . time() . '.'.$extension ); 
				Storage::disk('spaces')->put( $picture, file_get_contents($request->file('picture')->getRealPath()), 'public'); 
			} 
			$certification->update([ 
				'picture'		=> $picture,
				'description' 	=> $request->input('description')
			]);  
			return response()->json(['error'=>false, 'success'=>'Updating successfully!']);
		endif;
	} 
	public function deletecertification(Request $request, $id){ 
		$certification 	= 	Certification::where('user_id', Auth::user()->id)
										->where('serial', $id)
										->first(); 
		if(!isset($certification)){
			abort(404);
		}
		$certification->update([
			'is_deleted' => 1
		]);
		return redirect()->back()->with('message', "The certification is removed successfully");
	} 
	/************************************************ Role Stuff ******************************************/ 
	public function addrole(Request $request){
		$rules = array(
			'description'          => 'required',
			'hourly_rate_end'      => 'required | numeric',
			'subcategory'		   => 'required',
			'hourly_rate_from'     => 'required | numeric',
		); 
		$message= array(
			'description.required'			=> 'Please provide certification details.',
			'hourly_rate_from.required'		=> 'Please provide desired minimum rate.',
			'hourly_rate_from.numeric'   	=> 'Invalid format ',
			'hourly_rate_end.required'		=> 'Please provide desired maximum rate.',
			'hourly_rate_end.numeric'   	=> 'Invalid format',
		);   
		$validator 	= 	Validator::make($request->all(),$rules,$message);   
		if( $validator->fails() ): 
			return response()->json(['status'=> 0, 'errors'=>$validator->errors()]);
		else:   
			$subcategory 	=  SubCategory::where('serial', $request->subcategory)
										->first();
			if(!isset($subcategory))
				return response()->json(['status'=> 0, 'errors'=> ['subcategory' => 'Invalid Sub Category'] ]); 
			if($request->role !== null){
				$role = 	Role::where('serial', $request->role)
								->where('user_id', Auth::user()->id)
								->first();
				if(isset($role)){
					$role->update([
						'hourly_rate_from'  => $request->input('hourly_rate_from'),
						'hourly_rate_to'    => $request->input('hourly_rate_end'), 
						'role_title'		=> $request->input('role_title'),
						'subcategory'		=> $subcategory->id,
						'description'		=> $request->input('description')
					]);
				}
				else{
					return response()->json(['status'=> 0, 'errors'=> "Invalid ID"]);
				}
			}
			else{
				$role  = 	Role::create([
					'user_id' 			=> Auth::user()->id,
					'hourly_rate_from'  => $request->input('hourly_rate_from'),
					'hourly_rate_to'    => $request->input('hourly_rate_end'), 
					'role_title'		=> $request->input('role_title'), 
					'subcategory'		=> $subcategory->id,
					'description'		=> $request->input('description'), 
				]);
			}
			RoleSkill::where('role', $role->id)
					->update([
						'deleted' => 1
					]); 
			if($request->skills !== null){
				foreach($request->skills as $role_skill){
					RoleSkill::updateOrCreate([
						'role' 	=> $role->id,
						'skill' => $role_skill,
					],[
						'deleted' => 0
					]);		
				}
			}
			if($request->has('profile_start')){
				$user 						= 	UsersAccount::find(Auth::user()->id);
				//$user->industry 			= 	$request->job_type;
				$user->start_stage 			= 	10;
				$user->save();
			}
			return response()->json(['status'=> 1,'success'=>'Added successfully!']);
		endif;
	}
	public function getrole(Request $request){ 
		$validator = Validator::make($request->all(),  [  
            'role'     => 'required',
		]); 
        if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		
		$role = 	Role::where('user_id', Auth::user()->id)
						->where('serial', $request->role)
						->first();
		if(!isset($role)){
			return response()->json(['status' => 0, 'msg' => "Unknow role."], 200);
		}
		$this->role 		= 	$role;
		$this->skills   	= 	Skill::where('deleted', 0)->get(); 
		$this->industries 	= 	Industry::where('deleted', 0)->get(); 
		$html 				=   view("employee.profile.role_profile", $this->data)->render();
		return response()->json(array('status' => 1, 'msg' => 'success', 'html' => $html)); 
	} 
	public function deleterole(Request $request, $id){ 
		$role 	= 	Role::where('user_id', Auth::user()->id)
						->where('serial', $id)
						->first(); 
		if(!isset($role)){
			abort(404);
		}
		$role->update([
			'is_deleted' => 1
		]);
		return redirect()->back()->with('message', "The role is removed successfully");
	}
	/*********************************************** Profile Start ********************************************/
	public function start(Request $request){
		if(Auth::user()->phone_verified_status == 0){
			$verified_status = Auth::user()->verified_status('phone'); 
			if($verified_status)
				return view('general.phoneverification');
			else
				return view('general.limitphoneverification');
		}
		
		if( Auth::user()->accounts->start_stage == 10 )
			return redirect()->route('employee.profile'); 
		$this->user  = 	Auth::user();  
		if( Auth::user()->accounts->start_stage == '3'){
			$this->skills   			= 	Skill::where('deleted', 0)->get(); 
			$this->industries  			= 	Industry::where('deleted', 0)->get();  
		}
		
		return view('employee/start/start', $this->data);
	}
	public function getavailability(Request $request){
		$this->user = Auth::user();
		$html 		= view('employee.profile.partial.availability', $this->data)->render();
		return response()->json(['status' => 1, 'html' => $html ,'msg' => 'success']);
	}

	public function gettraveldistance(Request $request){
		$this->user = Auth::user();
		$html 		= view('employee.profile.partial.distance', $this->data)->render();
		return response()->json(['status' => 1, 'html' => $html ,'msg' => 'success']);
	}

	public function updateavailability(Request $request){
		$validator = Validator::make($request->all(),  [  
            'availiability'     => 'required',
		]); 
        if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		
		$user_status = '';
		if($request->availiability == "active")
			$user_status = "active";
		if($request->availiability == "inactive")
			$user_status = "inactive";	

		if($user_status == "")
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		$user = Auth::user();
		$user->availability = $user_status;
		$user->save();
		return response()->json(['status' => 1, 'msg' => "success"], 200); 
	}
	public function traveldistance(Request $request){
		$input_arr = array(
			'travel_distance' => $request->input('travel_distance')          
		);
		$user 					= 	UsersAccount::find(Auth::user()->id);
		$user->travel_distance 	= 	$request->input('travel_distance');
		$user->save();  
		if($request->has('profile_start')){
			$user->start_stage = 2;
			$user->save();
		} 
		return response()->json(['error'=>false,'success'=>'Updated successfully!']); 
	}
}