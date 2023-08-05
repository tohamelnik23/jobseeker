<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Model\Skill;
use DB, Auth, Redirect;
use App\Model\Notification;  
use App\Model\SlackInfo;
use Carbon\Carbon;
use App\Model\Industry;
use App\Model\Job;
use App\Model\BankInformation;
use App\Model\Account_verification;
use App\Helpers\Mainhelper; 
use Illuminate\Support\Facades\Validator;
class HomeController extends  BaseController{
    public function __construct()
    {
        //$this->middleware('auth');
	} 
	public function mainHomePage(Request $request){ 
		if(Auth::check()){
			$role = Auth::user()->role;  
			if($role == 3){
				return redirect()->route('master.dashboard'); 
			}
			if($role == 2){
				return redirect()->route('employer.jobs');
			}
			if($role == 1){ 
				return redirect()->route('search');
			} 
		}
		else{
			$PublicIP 			=  $request->ip();
			$PublicIP 			=  "198.255.66.27";
			$this->postal_code 	= 	Mainhelper::getZipByIP($PublicIP);
			$this->categories 	= 	Industry::where('deleted', 0)
											->get();
			return view('welcome', $this->data);
		}  
	} 
	public function testcall(Request $request){
		//SlackInfo::postMessageForError("notifications", "test call");
	}
    public function index(){
        $role = Auth::user()->role; 
		if($role == 3){
			return redirect()->route('master.dashboard'); 
		}
		if($role == 2){
			if(Auth::user()->accounts->city)
				return redirect()->route('employer.jobs');
			else
				return redirect()->route('employer.profile');
		}
		if($role == 1){
			if(Auth::user()->accounts->start_stage !== 10)
				return redirect()->route('employee.profile');

			return redirect()->route('search');
        }
        return redirect()->route('login');
    } 
	public function skills(){
		$result=array();
		$user = Auth::user();
		$user_skills = DB::table('skill_user')->where('user_id',$user->id)->pluck('skill_id')->toArray();
		
		$query = Skill::where('skill','LIKE', '%'.$_GET['query'].'%' )
				->whereNotIn('id', $user_skills);
		$result =  $query->pluck('skill')->toArray();
		echo json_encode($result);
	} 
	public function skillsemployer(){
		$result=array();
		$query = Skill::where('skill','LIKE', '%'.$_GET['query'].'%' );
		$result =  $query->pluck('skill')->toArray();
		echo json_encode($result);
    }  
    public function logout(Request $request) {
		if(Auth::guard('web')->check()){
            auth::logout(); 
            return redirect()->route('login');
        } 
    }  
    /************************************************ Notification *********************************************/
	public function notifications(Request $request){ 
		$now = Carbon::now(); 
		$this->today_notifications 	= 	Notification::where('notifications_touser',  Auth::user()->id)
												->orderBy("created_at", "desc")
												->where('created_at', '>=' , $now->toDateString() . ' 00:00:00')
												->where('created_at', '<=' , $now->toDateString() . ' 23:59:59')
												->where('notifications_deleted', 0)
												->get();
		$this->earlier_notifications 	= 	Notification::where('notifications_touser',  Auth::user()->id)
												->orderBy("created_at", "desc") 
												->where('created_at', '<' , $now->toDateString() . ' 00:00:00')
												->where('notifications_deleted', 0)
												->take(30)
												->get();
		return view('general.notifications', $this->data);
	}																				
	public function deletenotification(Request $request, $id){ 
        $notification   =   Notification::where('notifications_serial', $id)
                                    ->where('notifications_touser', Auth::user()->id)
									->where('notifications_deleted', 0)
                                    ->first();
        if(isset($notification)){
			$notification->update([
				'notifications_deleted' => 1
			]);
		}
        return redirect()->route('notifications');
	}
	/************************************************ Setting *********************************************/
	public function notification_settings(Request $request){
		if($request->isMethod('post')){
			$user = Auth::user();
			$validator = Validator::make($request->all(),  [
				'setting_key'      => 'required | max:512',
				'setting_value'    => 'required | max:512'
			]);
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
			if($request->has('setting_key')){
				$user->addSettingValue($request->setting_key,  $request->setting_value); 
			}
			return response()->json(['status' => 1, 'msg' => "success"], 200);
		}
		$this->setting_tab = "notifications";
		return view('general.setting', $this->data);
	}
	public function bankcard_settings(Request $request){
		$bank_information 	= 	BankInformation::where('user_id', Auth::user()->id)
													->first();
		if($request->isMethod('post')){
			$validator = Validator::make($request->all(),  [
				'bank_name'        => 'required | max: 512',
				'routing_number'   => 'required | numeric',
				'account_number'   => 'required | numeric',
				'request_type'     => 'required | max: 10', 
			]);
			if($validator->fails())
				return redirect()->back()->withInput()->withErrors($validator);
			$bank_information 	= 	BankInformation::updateOrCreate([
													'user_id' 	=> Auth::user()->id
												],[
													'bank_name' 		=> $request->bank_name,
													'routing_number' 	=> $request->routing_number,
													'account_number' 	=> $request->account_number,
												]);
			if($request->request_type == "verify"){
				Notification::create([
					'notifications_fromuser' 	=>  Auth::user()->id,
					'notifications_touser'		=>  0,
					'notifications_value'		=> 'The bank info needs to be verified',
					'notifications_type'		=> 'bank_verification'
				]);
				$bank_information->update([
					'verification_status' => 1
				]);
			}
		} 
		$this->bank_information 	= 	BankInformation::where('user_id', Auth::user()->id)
													->first();
		$this->setting_tab = "bank_information";
		return view('general.bank_card', $this->data);
	}
	/********************************************** Phone Verify ******************************************/
	public function request_phone_verification(Request $request){
		$phone_number = Auth::user()->cphonenumber;
		// check how many times
		$verified_status = Auth::user()->verified_status('phone'); 
		if(!$verified_status)
			return response()->json(['status' => 0, 'redirect' => 1 ,'msg' => "Too many times."], 200); 
		$account_verification = Account_verification::create([
			'type' 		=> 'phone',
			'user_id'	=>  Auth::user()->id
		]);
		$sms_string = "Hello, " . Auth::user()->accounts->firstname . ". This is the verification code " . $account_verification->code; 
		try{
			Mainhelper::sendSMSbyTwilio( Auth::user()->cphonenumber, $sms_string); 
		}
		catch(Exception $e){
			return response()->json(['status' => 0, 'redirect' => 0 , 'msg' => "Internal Error. Please try again"], 200); 
		}
		return response()->json(['status' => 1, 'msg' => "success."], 200); 
	}
	public function verify_phone_verification(Request $request){
		$validator = Validator::make($request->all(),  [  
			'code'     	=> 'required | min:4 | max:4',
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'redirect' => 0,  'msg' => "Invalid requirement"], 200);   
		$account_verification 	= 	Account_verification::where('user_id',  Auth::user()->id)
										->where('type', 'phone')
										->where('code', $request->code)
										->where('cleared', 0)
										->where('trying', '<=', 5)
										->first();			
		if(!isset($account_verification)){
			$account_verification 	= 	Account_verification::where('user_id',  Auth::user()->id)
											->where('type', 'phone') 
											->where('cleared', 0) 
											->first();
			$retry_times 	= 	0;
			if(isset($account_verification)){
				$retry_times 		= 	$account_verification->trying + 1; 
				Account_verification::where('user_id',  Auth::user()->id)
							->where('type', 'phone') 
							->where('cleared', 0) 
							->update([
								'trying' => $retry_times
							]);
				if($retry_times > 5)
					return response()->json(['status' => 0, 'redirect' => 1,  'msg' => "Too many Times"], 200);
			} 
			return response()->json(['status' => 0, 'redirect' => 0,  'msg' => "Invalid Code"], 200);
		}		
		$user = Auth::user();
		$user->phone_verified_status = 1;
		$user->save(); 
		return response()->json(['status' => 1,  'msg' => "Success"], 200); 
	}
}