<?php
namespace App\Http\Controllers\Employer\Jobs;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth,  Session, DB;
use App\Model\Offer;
use App\Model\Card;
use App\Model\Transaction;
use App\Model\SubmitWork;
use App\Model\DiscovergigTransaction;
use App\Model\Escrow;
use App\Model\MasterSetting;
use App\Model\Feedback;
use Carbon\Carbon;
use App\Helpers\Mainhelper;
use App\Model\TimeSheet;
use App\Model\Notification;   
use App\Http\Controllers\BaseController;
class ContractController extends BaseController{ 
    public function __construct(){
		$this->middleware(array('auth','employer'));
	}
	public function contract_details(Request $request, $id){ 
		$offer = 	Offer::where('serial', $id)
						->where('employer_id', Auth::user()->id)
						->first();
		if(!isset($offer))
			abort(404);
		$this->offer = $offer;

		if($offer->payment_type == "hourly"){ 
			return view('employer.jobs.contract_details_hourly', $this->data);
		} 
		else
			return view('employer.jobs.contract_details', $this->data); 
	} 
	public function contract_payment(Request $request, $id){
		$offer = 	Offer::where('serial', $id)
						->where('employer_id', Auth::user()->id)
						->first();
		if(!isset($offer)){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "The offer is not exist."], 200); 
			else
				abort(404);
		}

		if($offer->status !== 1){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "The offer is ended."], 200); 
			else
				return redirect()->route('employer.contract_details', $offer->serial)->with('error', "The offer is ended.");
		}

		$this->offer 		= $offer;
		//check current milestone and pay
		$current_milestone  = $offer->getCurrentMilestone();

		if(!isset($current_milestone)){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "There is no remaining milestone."], 200);
			else
				return redirect()->route('employer.contract_details', $offer->serial)->with('error', "There is no remaining milestone.");
		}

		if($current_milestone->status == "inactive"){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "Current milestone is not active yet."], 200);
			else
				return redirect()->route('employer.contract_details', $offer->serial)->with('error', "Current milestone is not active yet.");
		}
		
		if($current_milestone->deposit_status == 0){
			if($request->ajax())
				return response()->json(['status' => 0, 'msg' => "Current milestone is not deposited."], 200);
			else
				return redirect()->route('employer.contract_details', $offer->serial)->with('error', "Current milestone is not deposited.");
		}
		
		$this->current_milestone = $current_milestone;	 
		if($request->isMethod('post')){  
			$validator = Validator::make($request->all(),  [
				'release_amount'    => 'required | numeric',
				'contract_status'   => 'required | max: 100'
			]); 
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);	 
			//check escrow amount greater than release amount
			$current_escrow = $offer->getTotalEscrow();
			if($request->release_amount > $current_escrow)
				return response()->json(['status' => 0, 'msg' => "The amount cannot be greater than escrow."], 200);	 
			//if bonus is asked then check required amount
			//desposit fund if the bonus is selected
			if($request->bonus_amount){
				$total_amount =  (float) $request->release_amount + (float) $request->bonus_amount;
				$remain_amount = $total_amount -  $current_escrow;  
				if($remain_amount > 0){
					$sendData 			= 	array();
					$sendData['refId']	= 	$current_milestone->serial;

					$job_poster_fee 		=   MasterSetting::getValue('job_poster_fee');
					$escrow_fee     		=   100 - $job_poster_fee;
					$remain_amount_fund 	=   number_format($remain_amount * 100 / $escrow_fee, 2);
					$service_fee_bonus		=   number_format($remain_amount_fund -  $remain_amount, 2); 
					$cards 					= 	Card::where('user_id', Auth::user()->id)
												->where('status', 'verified')
												->get();
					if(!count($cards)){
						return response()->json(['status' => 0, 'msg' => "The card is not exist or not verified. Please try with another one."], 200);	 
					}
					$flag 	= 	0;
					foreach($cards as $card){
						$pay_result   		=   $card->payOrderWithProfile($remain_amount_fund, $sendData);  
						if($pay_result['status']){
							$transaction	 	= 	Transaction::create([
								'amount'        =>  $remain_amount_fund,
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
							$escrow 	= 	Escrow::create([
												'amount' 	 => $remain_amount_fund,
												'ref_id' 	 => $transaction->id,
												'offer_id'	 => $offer->id,
												'status'	 => 'available',
												'type'	 	 => "Payment",
												'description'=> "Paid from " . $card->card_type . ' for Bonus',
												'direction'  => 'in',
												'user_id'	 => Auth::user()->id
							]);
							$escrow 	= 	Escrow::create([
												'amount' 	 => $service_fee_bonus,
												'ref_id' 	 => $transaction->id,
												'offer_id'	 => $offer->id,
												'status'	 => 'available',
												'type'	 	 => "Processing Fee",
												'description'=> "Payment Processing Fee", 
												'direction'	 => 'out',
												'user_id'	 => Auth::user()->id
											]);
						}
					}
					if(!$flag){
						$error_msg = "There was a problem in the card.  Please retry after the check the card.";  
						return response()->json(array('status' => 0, 'msg' => $error_msg), 200);	
					}
				}
			}
			else{
				$total_amount =  (float) $request->release_amount;
			}
			//create client transaction  
			$description_html 	= view('employer.jobs.partial.discovergig_transaction', $this->data)->render(); 
			$freelancer 		= $offer->getFreelancer(); 
			// amount, bonus, service fee
			$job_taker_fee 				=  MasterSetting::getValue('job_taker_fee');
			$service_fee_main			=  number_format( $request->release_amount * $job_taker_fee / 100, 2);
			$discovergig_transaction 	=	DiscovergigTransaction::create([
												'amount' 		=> $request->release_amount,
												'type'	 		=> "Fixed Price",
												'description'	=> "Invoice for " .  $description_html,
												'direction'		=> 'in',
												'user_id'		=>  $freelancer->id,
												'offer_id'		=>  $offer->id,
												'ref_id'		=>  $current_milestone->id,
												'status'		=> 'pending'
											]); 
			$discovergig_transaction 	=	DiscovergigTransaction::create([
												'amount' 		=> $service_fee_main,
												'type'	 		=> "Service Fee",
												'description'	=> "Service Fee for Fixed Price - Ref ID " .  $discovergig_transaction->serial,
												'direction'		=> 'out',
												'user_id'		=>  $freelancer->id, 
												'offer_id'		=>  $offer->id,
												'ref_id'		=>  $current_milestone->id,
												'status'		=> 'pending'
											]);
			$escrow 	= 	Escrow::create([
								'amount' 		=> $request->release_amount,
								'ref_id' 		=> $current_milestone->id,
								'offer_id'		=> $offer->id,
								'status'		=> 'available',
								'type'	 		=> "Fixed Price",
								'direction'		=> 'out',
								'description' 	=> "Invoice from escrow for " .  $description_html,
								'user_id'		=> Auth::user()->id
							]);
			if($request->bonus_amount){ 
				$discovergig_transaction = DiscovergigTransaction::create([
					'amount' 		=> $request->bonus_amount,
					'type'	 		=> "Bonus",
					'description'	=> "Bonus for " .  $current_milestone->headline,
					'direction'		=> 'in',
					'user_id'		=>  $freelancer->id, 
					'offer_id'		=>  $offer->id,
					'ref_id'		=>  $current_milestone->id,
					'status'		=> 'pending'
				]); 
				$service_fee_bonus 			=  number_format( $request->bonus_amount * $job_taker_fee / 100, 2);
				$discovergig_transaction 	=	DiscovergigTransaction::create([
													'amount' 		=> $service_fee_bonus,
													'type'	 		=> "Service Fee",
													'description'	=> "Service Fee for Bonus - Ref ID " .  $discovergig_transaction->serial,
													'direction'		=> 'out',
													'user_id'		=>  $freelancer->id, 
													'offer_id'		=>  $offer->id,
													'ref_id'		=>  $current_milestone->id,
													'status'		=> 'pending'
												]);
				
				$escrow 	= 	Escrow::create([
					'amount' 		=> $request->bonus_amount,
					'ref_id' 		=> $current_milestone->id,
					'offer_id'		=> $offer->id,
					'status'		=> 'available',
					'type'	 		=> "Bonus",
					'direction'		=> 'out',
					'description' 	=> "Bonus for Milestone " . ($current_milestone->milestone_sort + 1) . " : " .  $current_milestone->headline,
					'user_id'		=> Auth::user()->id
				]); 
			} 
			//set the milestone as approved and require is is solved
			$current_milestone->update([
				'paid_amount'	=> $request->release_amount,
				'end_date'		=> date("Y-m-d H:i:s"),
				'status' 		=> 'approved'   	
			]);
			SubmitWork::where('milestone_id', $current_milestone->id)
						->where('status', 'active')
						->update([
							'status' => 'closed'
						]);  
			//check the type as continue or end. if end then we need to close the offer.
			if($request->contract_status == "end"){
				$offer->update([
					'end_time'  => date('Y-m-d H:i:s'),
					'status' 	=> 10
				]);
				Notification::create([
					'notifications_fromuser' 	=>  Auth::user()->id,
					'notifications_touser'		=>  $offer->user_id,
					'notifications_value'		=>  'The contract is ended for "' .  $offer->contract_title  .'".',
					'notification_ref'          =>  route('jobs_contract_details',   $offer->serial),
					'notifications_type'		=>  'contract_sent'
				]);
			}
			return response()->json(array('status' => 1, 'msg' => "success"), 200);
		}
		return view('employer.jobs.contract_payment', $this->data);
	}
	public function give_bonus(Request $request, $id){
		$offer = 	Offer::where('serial', $id)
						->where('employer_id', Auth::user()->id)
						->first();
		if(!isset($offer)) 
			return response()->json(['status' => 0, 'msg' => "The offer is not exist."], 200);
		
		if($offer->status !== 1)
		  	return response()->json(['status' => 0, 'msg' => "The offer is ended."], 200); 
		
		$validator = Validator::make($request->all(),  [
			'bonus_amount'    => 'required | numeric',
			'bonus_reason'	  => 'required | max: 30',
			'card'   		  => 'required | max: 100'
		]); 
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200); 
		if($request->bonus_amount < 0)
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		$card 	= 	Card::where('serial', $request->card)
						->where('user_id', Auth::user()->id)
						->where('status', 'verified')
						->first();
		if(!isset($card)){
			return response()->json(['status' => 0, 'msg' => "The card is not exist or not verified. Please try with another one."], 200);	 
		}

		$current_escrow = 	 $offer->getTotalEscrow(); 
		$bonus_amount 	=    (float) $request->bonus_amount;
		$remain_amount 	=    $bonus_amount -  $current_escrow;

		if($remain_amount > 0){
			$sendData 				=  array();
			$sendData['refId']		=  $offer->serial;
			$job_poster_fee 		=  MasterSetting::getValue('job_poster_fee');
			$escrow_fee     		=  100 - $job_poster_fee;
			$remain_amount_fund 	=  number_format($remain_amount * 100 / $escrow_fee, 2);
			$service_fee_bonus		=  number_format($remain_amount_fund -  $remain_amount, 2);
			 
			$pay_result   			=   $card->payOrderWithProfile($remain_amount_fund, $sendData); 
			if($pay_result['status']){
				$transaction	 	= 	Transaction::create([
					'amount'        =>  $remain_amount_fund,
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
				$escrow 	= 	Escrow::create([
									'amount' 	 => $remain_amount_fund,
									'ref_id' 	 => $transaction->id,
									'offer_id'	 => $offer->id,
									'status'	 => 'available',
									'type'	 	 => "Payment",
									'description'=> "Paid from " . $card->card_type . ' for ' . $request->bonus_reason,
									'direction'  => 'in',
									'user_id'	 => Auth::user()->id
				]);
				$escrow 	= 	Escrow::create([
									'amount' 	 => $service_fee_bonus,
									'ref_id' 	 => $transaction->id,
									'offer_id'	 => $offer->id,
									'status'	 => 'available',
									'type'	 	 => "Processing Fee",
									'description'=> "Payment Processing Fee", 
									'direction'	 => 'out',
									'user_id'	 => Auth::user()->id
								]);
			}
			else{ 
				$error_msg = "There was a system problem.  Please retry in a few minutes.";  
				return response()->json(array('status' => 0, 'msg' => $error_msg), 200);
			}
		}
		$freelancer 				=   $offer->getFreelancer(); 
		$discovergig_transaction 	= 	DiscovergigTransaction::create([
											'amount' 		=> $request->bonus_amount,
											'type'	 		=> $request->bonus_reason,
											'description'	=> $request->bonus_reason . " for " .  $offer->contract_title,
											'direction'		=> 'in',
											'user_id'		=>  $freelancer->id,
											'offer_id'		=>  $offer->id,
											'status'		=> 'pending'
										]);		
		$job_taker_fee 				=   MasterSetting::getValue('job_taker_fee');  
		$service_fee_bonus			=   number_format( $request->bonus_amount * $job_taker_fee / 100, 2);
		$discovergig_transaction 	=	DiscovergigTransaction::create([
											'amount' 		=> $service_fee_bonus,
											'type'	 		=> "Service Fee",
											'description'	=> "Service Fee for Bonus - Ref ID" .  $discovergig_transaction->serial,
											'direction'		=> 'out',
											'user_id'		=>  $freelancer->id,
											'offer_id'		=>  $offer->id,
											'status'		=> 'pending'
										]);
		$escrow 					= 	Escrow::create([
											'amount' 		=> $bonus_amount,
											'offer_id'		=> $offer->id,
											'status'		=> 'available',
											'type'	 		=> $request->bonus_reason,
											'direction'		=> 'out',
											'description' 	=> $request->bonus_reason . " for " .   $offer->contract_title,
											'user_id'		=> Auth::user()->id
										]); 
		//bonus_amount
		if($request->bonus_reason == "Bonus") 
			$notification_string =  Auth::user()->accounts->name . ' sent you a bonus of $' . number_format($bonus_amount, 2)  . '.';
		else
			$notification_string =  Auth::user()->accounts->name . ' sent you an expense reimbursement of $' . number_format($bonus_amount, 2)  . '.';
		
		Notification::create([
			'notifications_fromuser' 	=>  Auth::user()->id,
			'notifications_touser'		=>  $freelancer->id,
			'notifications_value'		=>  $notification_string,
			'notification_ref'          =>  route('employee.reports.earnings_history'),
			'notifications_type'		=> 'bonus_sent'
		]); 
		return response()->json(array('status' => 1, 'msg' => "success"), 200);
	}
	public function activate_milestone(Request $request, $id){
		dd('developing');
	}
	public function end_contract(Request $request, $id){
		$offer = 	Offer::where('serial', $id)
						->where('employer_id', Auth::user()->id)
						->first();
		if(!isset($offer)) 
			return response()->json(['status' => 0, 'msg' => "The offer is not exist."], 200); 
		if($offer->status >= 10)
		  	return response()->json(['status' => 0, 'msg' => "The offer is ended."], 200);

		if($offer->payment_type == "hourly"){
			for($i = 0; $i < 2; $i++){
				$return_value = $offer->manualEscrowReleaseForTimeSheet($i);
				if($return_value == 0)
					continue; 
				if($return_value == -1)
					response()->json(['status' => 0, 'msg' => "Please check your card."], 200);
			} 
		}

		$offer->update([
			'end_time'  => date('Y-m-d H:i:s'),
			'status' 	=> 10
		]);

		Notification::create([
			'notifications_fromuser' 	=>  Auth::user()->id,
			'notifications_touser'		=>  $offer->user_id,
			'notifications_value'		=>  'The contract is ended for "' .  $offer->contract_title  .'".',
			'notification_ref'          =>  route('jobs_contract_details', $offer->serial),
			'notifications_type'		=> 'contract_sent'
		]);
		
		Session::flash("message", 'The job is completed successfully. Please leave the feedback.');
		return response()->json(['status' => 1, 'msg' => "success"], 200);
	} 
	public function leavefeedback(Request $request, $id){
		$offer = 	Offer::where('serial', $id)
						->where('employer_id', Auth::user()->id)
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

		$feedback = $offer->getFeedback( $offer->user_id );
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
									->where('type', 'client')
									->where('id', $request->ending_reason)
									->first();
								
			if(!isset($ending_reason))
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);	 
			
			$rate_total 	=  	$request->rate_skills * 2 + $request->rate_quality * 2  + $request->rate_availability * 1.5  + $request->rate_deadlines * 1.5  + $request->rate_communication * 1.5 + $request->rate_cooperation * 1.5; 
			$rate_total 	=  	number_format($rate_total / 10, 2); 
			$feedback 		= 	Feedback::create([
									'offer_id' 				=> $offer->id,
									'reason'				=> $ending_reason->id,
									'user_id'				=> $offer->user_id,
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
			return response()->json(array('status' => 1, 'msg' => "success", 'url' =>  route('employer.contract_details', $offer->serial)), 200);
		}
		$this->offer 		= $offer; 
		return view('employer.jobs.feedback', $this->data);
	} 
	public function get_time_sheet(Request $request, $id){
		$offer 	= 	Offer::where('serial', $id)
						->where('employer_id', Auth::user()->id)
						->first();
		if(!isset($offer))	 
			return response()->json(['status' => 0, 'msg' => "The offer is not exist."], 200); 
		$start_date 	= 	$request->start_date;
		$time_result 	=	$offer->getTimeSheet($start_date); 
		$result 		= 	array();
		$curr_week 		= 	Carbon::createFromFormat('Y-m-d', $time_result['first_week'])->startOfWeek(Carbon::MONDAY); 
		if( $time_result['curr_week'] ==   $time_result['first_week']){
			if($offer->status == 1)
				$this->enable_edit =  1;
			else
				$this->enable_edit =  0;
		}
		else{
			$this->enable_edit =  0;
		}
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
		$html 				= 	view('employer.jobs.partial.contract_timesheet', $this->data)->render(); 
		return response()->json(['status' => 1, 'msg' => 'success', 'html' => $html, 'prev_week' => $time_result['prev_week'], 'next_week' => $time_result['next_week']], 200);
	}
	public function pause_contract(Request $request, $id){
		$offer 	= 	Offer::where('serial', $id)
						->where('employer_id', Auth::user()->id)
						->first();
		if(!isset($offer))	 
			return response()->json(['status' => 0, 'msg' => "The offer is not exist."], 200);

		if($offer->status == 2){// paused
			return response()->json(['status' => 0, 'msg' => "The offer is paused already."], 200);
		}
		if($offer->status >= 10){
			return response()->json(['status' => 0, 'msg' => "The offer is finished already."], 200);
		}
		$offer->update([
			'status' => 2
		]);

		Notification::create([
			'notifications_fromuser' 	=>  Auth::user()->id,
			'notifications_touser'		=>  $offer->user_id,
			'notifications_value'		=>  'The contract is paused for "' .  $offer->contract_title  .'".',
			'notification_ref'          =>  route('jobs_contract_details', $offer->serial),
			'notifications_type'		=> 'contract_sent'
		]);
		// message
		
		return response()->json(['status' => 1, 'msg' => "success"], 200);

	}
	public function resume_contract(Request $request, $id){
		$offer 	= 	Offer::where('serial', $id)
						->where('employer_id', Auth::user()->id)
						->first();
		if(!isset($offer))	 
			return response()->json(['status' => 0, 'msg' => "The offer is not exist."], 200);

		if($offer->status != 2){// paused
			return response()->json(['status' => 0, 'msg' => "The offer is not paused."], 200);
		}
		$offer->update([
			'status' => 1
		]);
		Notification::create([
			'notifications_fromuser' 	=>  Auth::user()->id,
			'notifications_touser'		=>  $offer->user_id,
			'notifications_value'		=>  'The contract is resumed for "' .  $offer->contract_title  .'".',
			'notification_ref'          =>  route('jobs_contract_details', $offer->serial),
			'notifications_type'		=> 'contract_sent'
		]);
		// message
		return response()->json(['status' => 1, 'msg' => "success"], 200);
	}
}