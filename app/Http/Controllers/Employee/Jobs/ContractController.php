<?php
namespace App\Http\Controllers\Employee\Jobs;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use App\Model\Offer;
use App\Model\Milestone;
use App\Model\SubmitWork;
use App\Model\Feedback;
use App\Model\TimeSheet;
use Carbon\Carbon;
use App\Helpers\Mainhelper;
use App\Model\Notification; 
use App\Model\MasterSetting;
use App\Model\Card;
use App\Model\Transaction;
use App\Model\Escrow;
use App\Model\DiscovergigTransaction;

use Illuminate\Support\Facades\Validator;
class ContractController extends BaseController{
    public function __construct(){
		$this->middleware(array('auth','employee'));
    }
    // active contracts
    public function jobs(Request $request){ 
		$offers =  	Offer::where('offers.status', '<>', 0)
						->where('offers.status', '<', 10)
						->where('user_id', Auth::user()->id)
						->orderBy("start_time", "desc")
						->get();
		$this->contracts = $offers;
        return view('employee/jobs/jobs',$this->data); 
	} 
    // all contracts including ended
    public function contracts(Request $request){
        $offers_obj =  	Offer::where('offers.status', '<>'  ,0) 
							->where('user_id', Auth::user()->id);
		if($request->q){
			$offers_obj = $offers_obj->where('contract_title', 'like' ,'%' . $request->q . '%');
		}
		if($request->closed_contracts){
			if($request->closed_contracts == "on")
				$this->closed_contracts = "off";
			else
				$this->closed_contracts = "on";
		}
		else{
			$this->closed_contracts = "on";
		} 
		if($this->closed_contracts == "on"){
			$offers_obj 	= 	$offers_obj->where("status",  '<', 10);
		} 
		if($request->filter_by){
			$this->filter_by = $request->filter_by;
		}
		else{
			$this->filter_by = "start_date";
		}
		if($request->sort_by){
			$this->sort_by = $request->sort_by;
		}
		else{
			$this->sort_by = "desc";
		}
		switch($this->filter_by){
			case 'start_date':
				$offers_obj 	= 	$offers_obj->orderBy("start_time", $this->sort_by);
				break;
			case 'end_date':
				$offers_obj 	= 	$offers_obj->orderBy("end_time", $this->sort_by);
				break;	
			case 'contract_title': 
				$offers_obj 	= 	$offers_obj->orderBy("contract_title", $this->sort_by);
				break;
		}
		$offers = 	$offers_obj->paginate(5);
		$offers->appends($request->query()); 
		$this->contracts = $offers;
        return view('employee/jobs/contracts',$this->data);  
    }
	// contract details
	public function contract_details(Request $request, $id){ 
		$offer = 	Offer::where('serial', $id)
						->where('user_id', Auth::user()->id)
						->first();
		if(!isset($offer))
			abort(404); 
		$this->offer = $offer; 
		if($offer->payment_type == "hourly")
			return view('employee.jobs.contract_details_hourly', $this->data);
		else
			return view('employee.jobs.contract_details', $this->data);
	}
	public function milestone_details(Request $request){
		$validator = Validator::make($request->all(),  [
			'milestone_id'      => 'required | max: 20'
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		$milestone 	= 	Milestone::select("milestones.*")
								->join("offers", "offers.id", "milestones.offer_id")
								->where('offers.user_id', Auth::user()->id)
								->where('milestones.serial', $request->milestone_id)
								->first();
		if(!isset($milestone)){
			return response()->json(['status' => 0, 'msg' => "Invalid milestone"], 200);
		}
		$this->milestone 	= 	$milestone;
		$html = view('employee.jobs.partial.submit_work_detail', $this->data)->render(); 
		return response()->json(['status' => 1, 'msg' => "success", 'html' => $html], 200);
	}
	public function submitwork(Request $request){
		$validator = Validator::make($request->all(),  [
			'submit_amount'      => 'required | numeric',
			'submit_message'     => 'required | max: 5000',
			'milestone_id'       => 'required | max: 20',
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		
		$milestone 	= 	Milestone::select("milestones.*")
							->join("offers", "offers.id", "milestones.offer_id")
							->where('offers.user_id', Auth::user()->id)
							->where('milestones.serial', $request->milestone_id)
							->first(); 
		if(!isset($milestone)){
			return response()->json(['status' => 0, 'msg' => "Invalid milestone"], 200);
		} 
		// close all submitwork
		SubmitWork::where('milestone_id', $milestone->id)
				->where('status', 'active')
				->update([
					'status' => 'closed'
				]);
		$submitwork = 	SubmitWork::create([
							'milestone_id' 	=> $milestone->id,
							'user_id'	   	=> Auth::user()->id,
							'message'	   	=> $request->submit_message,
							'status'		=> 'active',
							'amount'		=> $request->submit_amount
						]); 
		///////////////////////////////////
		$offer = $milestone->getOffer(); 
		if(isset($offer)){
			Notification::create([
				'notifications_fromuser' 	=>  Auth::user()->id,
				'notifications_touser'		=>  $offer->employer_id,
				'notifications_value'		=>  Auth::user()->accounts->name . ' requested the payment for "' .  $offer->contract_title  .'".',
				'notification_ref'          =>  route('employer.contract_details', $offer->serial),
				'notifications_type'		=> 'payment_request'
			]);
		}
		return response()->json(['status' => 1, 'msg' => "success"], 200);
	}
	public function leavefeedback(Request $request, $id){
		$offer = 	Offer::where('serial', $id)
						->where('user_id', Auth::user()->id)
						->first();
		if(!isset($offer)){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "The offer is not exist."], 200); 
			else
				abort(404);
		} 
		if($offer->status !== 10){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "This offer is not ended."], 200); 
			else
				return redirect()->route('employer.contract_details', $offer->serial)->with('error', "The offer is not ended.");
		} 
		$feedback = $offer->getFeedback( $offer->employer_id );
		if(isset($feedback)){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "You give the feedback already."], 200); 
			else
				return redirect()->route('employer.contract_details', $offer->serial)->with('error', "You give the feedback already.");
		}

		if($request->isMethod('post')){  
			$validator = Validator::make($request->all(),  [
				'ending_reason'    	 	=> 'required | numeric',
				'recommend_value'    	=> 'required | numeric',
				'rate_skills'    	 	=> 'required | numeric',
				'rate_quality'    	 	=> 'required | numeric',
				'rate_availability'    	=> 'required | numeric',
				'rate_deadlines'    	=> 'required | numeric',
				'rate_communication'    => 'required | numeric',
				'rate_cooperation'    	=> 'required | numeric', 
				'feedback_note'   		=> 'nullable | max: 2000'
			]); 
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);	 
			$ending_reason 	=   DB::table('end_reason')
									->where('type', 'freelancer')
									->where('id', $request->ending_reason)
									->first(); 
			if(!isset($ending_reason))
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);	 
				
			$rate_total 	=  	$request->rate_skills * 2 + $request->rate_quality * 2  + $request->rate_availability * 1.5  + $request->rate_deadlines * 1.5  + $request->rate_communication * 1.5 + $request->rate_cooperation * 1.5; 
			$rate_total 	=  	round($rate_total / 10, 2); 
			$feedback 		= 	Feedback::create([
									'offer_id' 				=> $offer->id,
									'reason'				=> $ending_reason->id,
									'user_id'				=> $offer->employer_id,

									'rate_skills'			=> $request->rate_skills,
									'rate_quality'			=> $request->rate_quality, 
									'rate_availability'		=> $request->rate_availability,
									'rate_deadlines'		=> $request->rate_deadlines,
									'rate_communication'	=> $request->rate_communication,
									'rate_cooperation'		=> $request->rate_cooperation,
									'rate_total'			=> $rate_total,
									'recommend_value'		=> $request->recommend_value,
									'message'				=> $request->feedback_note
								]);

			Notification::create([
				'notifications_fromuser' 	=>  Auth::user()->id,
				'notifications_touser'		=>  $offer->user_id,
				'notifications_value'		=>  Auth::user()->accounts->name . ' leave the feedback for "' .  $offer->contract_title  .'".',
				'notification_ref'          =>  route('jobs_contract_details', $offer->serial),
				'notifications_type'		=> 'contract_sent'
			]);

			Session::flash("message", 'You leave the feedback successfully.');
			return response()->json(array('status' => 1, 'msg' => "success", 'url' =>  route('jobs_contract_details', $offer->serial)), 200);
		}
		$this->offer 		= $offer;
		return view('employee.jobs.feedback', $this->data);
	}
	public function get_time_sheet(Request $request, $id){
		$offer 	= 	Offer::where('serial', $id)
						->where('user_id', Auth::user()->id)
						->first();
		if(!isset($offer))	 
			return response()->json(['status' => 0, 'msg' => "The offer is not exist."], 200); 
		$start_date 	= 	$request->start_date;
		$time_result 	=	$offer->getTimeSheet($start_date);
		$result 		= 	array();
		$curr_week 		= 	Carbon::createFromFormat('Y-m-d', $time_result['first_week'])->startOfWeek(Carbon::MONDAY); 
		if( $time_result['curr_week'] ==   $time_result['first_week']){
			$this->enable_edit =  1;
		}
		else{
			$this->enable_edit =  0;
		} 

		if($offer->status != 1)
			$this->enable_edit =  0;

		for($i = 0; $i < 7; $i++){
			$current_item 				= 	array();
			$current_item['date']		=	$curr_week->format('m/d');
			$current_item['real_date']	=	$curr_week->toDateString();
			$current_item['week']		=	Mainhelper::getWeekName($curr_week->format('w'));
			$current_item['time_sheet']	=	TimeSheet::where('offer_id', $offer->id)
													->where('timesheets_date', $curr_week->toDateString())
													->first();
			$result[] 					=	$current_item;
			$curr_week->addDay();
		}
		$this->timesheets 	= 	$result;
		$this->offer		=	$offer;		
		$html 				= 	view('employee.jobs.partial.contract_timesheet', $this->data)->render(); 
		return response()->json(['status' => 1, 'msg' => 'success', 'html' => $html, 'prev_week' => $time_result['prev_week'], 'next_week' => $time_result['next_week']], 200);
	}
	public function updatetimesheet(Request $request, $id){
		$offer 	= 	Offer::where('serial', $id)
						->where('user_id', Auth::user()->id)
						->first();
		if(!isset($offer))	 
			return response()->json(['status' => 0, 'msg' => "The offer is not exist."], 200);
		if($offer->status == 10)
			return response()->json(['status' => 0, 'msg' => "This offer is ended."], 200);
		
		if($offer->status <> 1)
			return response()->json(['status' => 0, 'msg' => "This offer is not agree the contract."], 200); 
	
		$now        =  Carbon::now()->toDateString();
		$first_day  =  Carbon::now()->startOfWeek(Carbon::SUNDAY)->toDateString();  
		foreach( $request->timesheet_vals  as $timesheet_val){
			//if($now == $timesheet_val['date'])
			//	continue; 
			if(date('Y-m-d', strtotime($offer->start_time)) >  $timesheet_val['date'] )
				continue; 
			if(($now >= $timesheet_val['date']) && ( $first_day <= $timesheet_val['date'])){
				TimeSheet::updateOrCreate([
					'offer_id' 			=> $offer->id,
					'timesheets_date'	=> $timesheet_val['date']
				],[
					'timesheets_rate'  =>  $offer->amount,
					'timesheets_time'  =>  $timesheet_val['hour'],
					'deleted'		   =>  0
				]);
			}
		} 
		return response()->json(['status' => 1, 'msg' => "success"], 200);
	} 
}