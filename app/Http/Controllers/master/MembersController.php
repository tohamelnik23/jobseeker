<?php
namespace App\Http\Controllers\master; 
use App\Http\Controllers\BaseController;
use App\User;
use App\Model\Verification;
use App\Model\Notification;
use App\Model\DriverLicense;
use App\Model\BankInformation;
use App\Model\UsersAccount;
use App\Model\LoanRequest;
use App\Model\DiscovergigTransaction;
use App\Model\BorrowingChangeRequest;
use Auth;
use Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
class MembersController extends BaseController { 
	public function employees() {
		$data 					= 	array();
		$data['employees']		=  	User::leftJoin('user_account', 'user_account.account_id', 'users.id')
										->where('users.role', 1)
										->select("users.*","user_account.*")
										->paginate(15); 
		return view('master.members.employees', $data);
	}

	public function employers() {
		$data 					= 	array();
		$data['employers']		=  	User::leftJoin('user_account', 'user_account.account_id', 'users.id')
										->where('users.role', 2)
										->select("users.*","user_account.*")
										->paginate(15); 
		return view('master.members.employers', $data);
    }
	public function employeeslist(Request $request){ 
		$employees 	= 	new User();
		$list		= 	$employees->getemployeeslist($request,$request->input('length'),$request->input('start')); 
		$data 		= 	array();	
		$no 		= 	$request->input('start'); 			
		foreach ($list['result'] as $res) {
			$no++; 
			$html="";
			$row = array(); 
			$editurl = route('master.members.show', ['id' =>$res->id]);
			//$editurl = '#';
			$row[] =$res->name;
			$row[] =$res->email;
			$row[] =($res->profile_pic_verified_status == 1 || $res->address_verified_status == 1)?'yes':'no';
			$row[] = isset($res->created_at)?$res->created_at->format('m/d/Y'):'';		
			$row[] = '<a class="btn btn-success margin-5" href="'.$editurl.'">Edit</a>';			
			$data[] = $row;    
		} 
		$output = array(      
			"draw" => isset($_REQUEST['draw'])?intval($_REQUEST['draw']):'',     
			"recordsTotal" => intval($list['num']),    
			"recordsFiltered" => intval($list['num']),     
			"data" => $data,  
		);      
		echo json_encode($output); 	
	}
	public function employerslist(Request $request){ 
		$employees = new User();
		$list= $employees->getemployerslist($request,$request->input('length'),$request->input('start')); 
		$data = array();	
		$no = $request->input('start'); 
		foreach ($list['result'] as $res) {   
			$no++; 
			$html="";
			$row = array(); 
			$editurl = route('master.members.employer.show', ['id' =>$res->id]);
			$row[] =$res->name;
			$row[] =$res->email;
			$row[] =($res->profile_pic_verified_status == 1)?'yes':'no';
			$row[] = isset($res->created_at)?$res->created_at->format('m/d/Y'):'';		
			$row[] = '<a class="btn btn-success margin-5" href="'.$editurl.'">Edit</a>';			
			$data[] = $row;    
		}     
		 
		$output = array(      
			"draw" => isset($_REQUEST['draw'])?intval($_REQUEST['draw']):'',     
			"recordsTotal" => intval($list['num']),    
			"recordsFiltered" => intval($list['num']),     
			"data" => $data,  
		);      
		echo json_encode($output); 	
	}
	public function show($id){  
		$data['user'] 					=   User::findOrFail($id);
		$data['verification'] 			= 	Verification::where('user_id', $id)->where('type', 'address')->where('status', 'new')->get();
		$data['rverification'] 			= 	Verification::where('user_id', $id)->where('type', 'address')->where('status', 'rejected')->get();
		$data['averification'] 			= 	Verification::where('user_id', $id)->where('type', 'address')->where('status', 'verified')->get(); 

		$data['picverification'] 		= 	Verification::where('user_id', $id)->where('type', 'picture')->where('status', 'new')->get();
		$data['picrverification'] 		= 	Verification::where('user_id', $id)->where('type', 'picture')->where('status', 'rejected')->get();
		$data['picaverification'] 		= 	Verification::where('user_id', $id)->where('type', 'picture')->where('status', 'verified')->get(); 
		
		$data['driver_verification'] 	= 	Verification::where('user_id', $id)->where('type', 'driverlicense')->where('status', 'new')->get();
		$data['driver_rverification'] 	= 	Verification::where('user_id', $id)->where('type', 'driverlicense')->where('status', 'rejected')->get();
		$data['driver_averification'] 	= 	Verification::where('user_id', $id)->where('type', 'driverlicense')->where('status', 'verified')->get();
		return view('master/members/profile',$data);
	}
	public function employershow($id){
		$data['user'] 				= 	User::findOrFail($id); 
		$data['picverification'] 	= 	Verification::where('user_id', $id)->where('type', 'picture')->where('status', 'new')->get();
		$data['picrverification'] 	= 	Verification::where('user_id', $id)->where('type', 'picture')->where('status', 'rejected')->get();
		$data['picaverification'] 	= 	Verification::where('user_id', $id)->where('type', 'picture')->where('status', 'verified')->get(); 
		return view('master/members/employerprofile',$data);
	}
	public function getnote(Request $request){
		$validator = Validator::make($request->all(),  [  
            'document'     => 'required',
		]); 
        if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		
		$document = 	Verification::where('id', $request->document)
									->first();
		if(!isset($document)){
			return response()->json(['status' => 0, 'msg' => "Unknown document."], 200);
		}  
		$html =  view("master.members.notedetail", compact('document'))->render(); 
		return response()->json(array('status' => 1, 'msg' => 'success', 'html' => $html));  
	}
	public function addnote(Request $request){
		$note 		= Verification::findOrFail($request->id);
		$note->note = $request->note;
		$note->save(); 
		return response()->json(['error'=>false,'success'=>'Added successfully!']);
	}
	public function action(Request $request){
		$tag = $request->input('tag')?$request->input('tag'):''; 
		if($tag=="address_info_Approve"):
			$user= User::find($request->id);
			$user->address_verified_status='2';
			$user->save(); 
			Verification::where('user_id', $request->id)->where('status', 'new')->where('type', 'address')->update(['status' => 'verified']);  
			Notification::create([
				'notifications_fromuser' 	=> 0,
				'notifications_touser'		=> $user->id,
				'notifications_value'		=> 'Address Verification Approved',
				'notifications_type'		=> 'address_verification'
			]); 
			return response()->json(['error'=>false]);
		endif; 
		if($tag=="address_info_Reject"):
			$user= User::find($request->id);
			$user->address_verified_status='3';
			$user->save(); 
			Verification::where('user_id', $request->id)->where('status', 'new')->where('type', 'address')->update(['status' => 'rejected']); 
			Notification::create([
				'notifications_fromuser' 	=> 0,
				'notifications_touser'		=> $user->id,
				'notifications_value'		=> 'Address Verification Rejected',
				'notifications_type'		=> 'address_verification'
			]); 
			return response()->json(['error'=>false]);
		endif;
		if($tag=="profilepic_info_Approve"):
			$user= User::find($request->id);
			$user->profile_pic_verified_status='2';
			$user->save(); 
			Verification::where('user_id', $request->id)->where('status', 'new')->where('type', 'picture')->update(['status' => 'verified']); 
			Notification::create([
				'notifications_fromuser' 	=> 0,
				'notifications_touser'		=> $user->id,
				'notifications_value'		=> 'Profile Picture Verification Approved',
				'notifications_type'		=> 'profile_verification'
			]); 
			return response()->json(['error'=>false]);
		endif; 
		if($tag=="profilepic_info_Reject"):
			$user= User::find($request->id);
			$user->profile_pic_verified_status='3';
			$user->save(); 
			Verification::where('user_id', $request->id)->where('status', 'new')->where('type', 'picture')->update(['status' => 'rejected']); 
			Notification::create([
				'notifications_fromuser' 	=> 0,
				'notifications_touser'		=> $user->id,
				'notifications_value'		=> 'Profile Picture Verification Rejected',
				'notifications_type'		=> 'profile_verification'
			]); 
			return response()->json(['error'=>false]);
		endif;
		if($tag=="driverlicense_info_Approve"): 
			DriverLicense::updateOrCreate([
				'user_id' 			=>  $request->id
			],[
				'verified'			=>  '2'
			]); 
			Verification::where('user_id', $request->id)->where('status', 'new')->where('type', 'driverlicense')->update(['status' => 'verified']); 
			Notification::create([
				'notifications_fromuser' 	=> 0,
				'notifications_touser'		=> $request->id,
				'notifications_value'		=> 'Driver License Verification Approved',
				'notifications_type'		=> 'driverlicense_verification'
			]); 
			return response()->json(['error'=>false]);
		endif;		
		if($tag=="driverlicense_info_Reject"):
			DriverLicense::updateOrCreate([
				'user_id' 			=>  $request->id
			],[
				'verified'			=>  '3'
			]);   
			Verification::where('user_id', $request->id)->where('status', 'new')->where('type', 'driverlicense')->update(['status' => 'rejected']); 
			Notification::create([
				'notifications_fromuser' 	=> 0,
				'notifications_touser'		=> $request->id,
				'notifications_value'		=> 'Driver License Verification Rejected',
				'notifications_type'		=> 'driverlicense_verification'
			]); 
			return response()->json(['error'=>false]);
		endif; 

		if($tag=="bankcard_info_Approve"): 
			BankInformation::updateOrCreate([
				'user_id' 				=>  $request->id
			],[
				'verification_status'	=>  '3'
			]);
			Notification::create([
				'notifications_fromuser' 	=> 0,
				'notifications_touser'		=> $request->id,
				'notifications_value'		=> 'The bank verification is approved',
				'notifications_type'		=> 'bank_verification'
			]); 
			return response()->json(['error'=>false]);
		endif;		
		if($tag=="bankcard_info_Reject"):
			BankInformation::updateOrCreate([
				'user_id' 				=>  $request->id
			],[
				'verification_status'	=>  '2'
			]); 
			Notification::create([
				'notifications_fromuser' 	=> 0,
				'notifications_touser'		=> $request->id,
				'notifications_value'		=> 'The bank verification is rejected',
				'notifications_type'		=> 'bank_verification'
			]); 
			return response()->json(['error'=>false]);
		endif;
	}
	public function loans(Request $request, $id){
		$this->user		=   User::findOrFail($id); 
		if($request->isMethod('post')){
			$validator = Validator::make($request->all(),  [
				'loan_fee'     		=> 'required | numeric',
				'loan_amount'     	=> 'required | numeric'
			]); 
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
			$useraccount 				= UsersAccount::find($this->user->id);
			$useraccount->loan_fee		= $request->loan_fee;
			$useraccount->loan_amount	= $request->loan_amount;
			$useraccount->save();
			return response()->json(['status' => 1, 'msg' => 'success'], 200);
		} 
		$this->loan_histories 	=  	LoanRequest::where('user_id', $id) 
										->orderBy("created_at", 'desc')
										->get();
		
		$borrowing_change_request   =   BorrowingChangeRequest::where('user_id',  $id)
											->where('status', 0)
											->orderBy('created_at', 'desc')
											->first();
		if(isset($borrowing_change_request)){
			$borrowing_change_request->update([
				'status' => 1
			]);
		}
		$this->borrowing_change_request = $borrowing_change_request;
		return view('master/members/loans', $this->data);
	}
	public function loans_action(Request $request, $id){
		$this->user		=   User::findOrFail($id);
		$validator 		=	Validator::make($request->all(),  [
								'request_type'      => 'required | max: 20',
								'loan_request'     	=> 'required | numeric'
							]); 
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
	
		$loan_request = 	LoanRequest::where('user_id', $this->user->id)
									->where('serial', $request->loan_request)
									->where('status', 'pending')
									->first();
		if(!isset($loan_request))
			return response()->json(['status' => 0, 'msg' => "This request is taken already."], 200);
		
		if($request->request_type == "approve"){
			$loan_fee 		=	 100 - $this->user->accounts->loan_fee;
			$total_paid 	=    round($loan_request->agreed_amount * 100 / $loan_fee, 2);
			$service_fee 	=	 $total_paid -   $loan_request->agreed_amount;
			$loan_request->update([
				'status' 		=> 'paid',
				'total_paid'	=> $total_paid
			]);
			$discovergig_transaction 	=	DiscovergigTransaction::create([
													'amount' 		=> $loan_request->agreed_amount,
													'type'	 		=> "Loan Request",
													'description'	=> "Paid for Loan Request in " .  date( 'F d, Y', strtotime($loan_request->created_at)),
													'direction'		=> 'out',
													'user_id'		=>  $this->user->id, 
													'ref_id'		=>  $loan_request->id,
													'status'		=> 'available'
											]);
			$discovergig_transaction 	=	DiscovergigTransaction::create([
							'amount' 		=> $service_fee,
							'type'	 		=> "Loan Fee",
							'description'	=> "Loan Fee for Loan Request - Ref ID " .  $discovergig_transaction->serial,
							'direction'		=> 'out',
							'user_id'		=>  $this->user->id,
							'ref_id'		=>  $loan_request->id,
							'status'		=> 'available'
						]); 
			// notification 
			return response()->json(['status' => 1, 'msg' => "success"], 200);
		}
		if($request->request_type == "reject"){
			$loan_request->update([
				'status' => 'rejected'
			]); 
			// notification
			return response()->json(['status' => 1, 'msg' => "success"], 200);
		}
	}
	public function loans_setting(Request $request, $id){
		$this->user		=   User::findOrFail($id);
		$validator 		=	Validator::make($request->all(),  [ 
								'loan_visible'     	=> 'required | numeric'
							]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		$useraccount 				= UsersAccount::find($this->user->id); 
		$useraccount->loan_enable	= $request->loan_visible;  
		$useraccount->save();
		return response()->json(['status' => 1, 'msg' => "success."], 200);
	}
}