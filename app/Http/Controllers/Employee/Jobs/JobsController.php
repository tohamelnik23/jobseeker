<?php
namespace App\Http\Controllers\Employee\Jobs;
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
use App\Model\Job;
use App\Model\Skill;
use App\Model\Proposal;
use App\Model\SavedJob;
use App\Model\Invite;
use App\Model\Decline;
use App\Model\Role;
use App\Model\Offer;
use App\Model\MessageList;
use App\Model\Message;
use App\Model\Notification;
use App\Model\LoanRequest;
use App\Http\Controllers\BaseController;
use App\Helpers\Mainhelper;
class JobsController extends BaseController{
    public function __construct(){
		$this->middleware(array('auth','employee'));
    } 		
	public function proposals(Request $request){   
		$this->submitted_proposals =  Proposal::getProposals(Auth::user()->id,  'submitted');
		$this->active_proposals    =  Proposal::getProposals(Auth::user()->id,  'active');
		$this->invitations 		   =  Invite::getInvites(Auth::user()->id);
		$this->declined_interviews =  Decline::getProposals(Auth::user()->id, 'invite');   
		$this->declined_proposals  =  Decline::getProposals(Auth::user()->id, 'proposal');
		$this->offers 			   =  Offer::getOffers(Auth::user()->id);
		return view('employee.jobs.proposals', $this->data);
	} 

	public function saved(Request $request){ 
		$jobs   =   Job::where('jobs.status', 1)
						->join('savedjobs', 'savedjobs.job_id', 'jobs.id')
						->where('savedjobs.user_id', Auth::user()->id)
						->where('savedjobs.status', 1)
                        ->orderBy("jobs.updated_at", "desc")
						->select("jobs.*")
        				->paginate(10); 
        $this->jobs     =  $jobs;   
		$this->tab_type =  'saved';
        return view('search.search', $this->data);  
	}
	public function saveaction(Request $request){
		$validator = Validator::make($request->all(),  [
			'job_id'     	=> 'required'
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);  
		$job 	=	Job::where("serial", $request->job_id) 
						->first();
		if(!isset($job))
			return response()->json(['status' => 0, 'msg' => "cannot find this job."], 200); 
		$request_type =  $job->SavedJob(Auth::user()->id); 
		if($request_type == 0)  $request_type = 1;
		else 	 				$request_type = 0; 
		SavedJob::updateOrCreate([
			'user_id' 	=> Auth::user()->id,
			'job_id'	=> $job->id
		],[
			'status'   => $request_type
		]);  
		return response()->json(['status' => 1, 'msg' => "success."], 200);
	} 
	public function invites_details(Request $request, $id){ 
        $invite =   Invite::where('serial', $id)
						->where('user_id', Auth::user()->id) 
						->first(); 
        if(!isset($invite))
            abort(404);
		// check proposal
		$proposal   =  Proposal::where('job_id', $invite->job_id)
							->where('user_id',Auth::user()->id)
							->first();
		if(isset($proposal)){
			Session::flash('error', 'You have accepted the invitation already.');
			return redirect()->route('jobs_proposal_details', $proposal->serial);
		} 
		if($invite->status == 1){
			Session::flash('error', 'You have accepted the invitation already.');
			return redirect()->route('jobs_proposal_details', $proposal->serial);
		}  
		$job = $invite->getJob(); 
		if($job->status == 2){
			Session::flash('error', 'This job is closed already.');
			return redirect()->route('jobs_details', $job->serial);
		} 
		if($invite->status != 0){
			Session::flash('error', 'This interview is declined already.');
			return redirect()->route('jobs_details', $job->serial);
		} 
        $this->invite 			=   $invite;
		$this->user 			= 	User::find(Auth::user()->id); 
		$this->decline_messages =   DB::table("decline_reasons")->where('type', "invite_freelancer")->orderBY("more_info")->get(); 
        return view('employee.jobs.detail', $this->data);
    }
	// Decline action
	public function declineaction(Request $request, $request_type, $id){ 
		$suggested_url = ['invitation', 'proposal', 'offer']; 
		if(!in_array( $request_type, $suggested_url)){
			return response()->json(['status' => 0, 'msg' => "invalid request"], 200);
		}  
		$validator = Validator::make($request->all(),  [
			'reason'     	=> 'required'
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);  
		
		if(($request_type == "invitation") || ($request_type == "offer")) 
			$reason = DB::table("decline_reasons")->where('type', "invite_freelancer")->first(); 
		if($request_type == "proposal")
			$reason = DB::table("decline_reasons")->where('type', "proposal_freelancer")->first(); 
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
			case 'invitation':
				$invite = 	Invite::where('serial', $id)
								->where('user_id', Auth::user()->id)
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
				],[
					'decline_reference' => $invite->serial,
					'decline_reason' 	=> $request->reason,
					'decline_user' 	 	=> Auth::user()->id,
					'decline_note' 	 	=> $request->decline_notes,
					'other_reason' 	 	=> $request->other_reason
				]); 
				$invite->update([
					'status' => 2
				]); 
				$job = $invite->getJob(); 
				Notification::create([
					'notifications_fromuser' 	=>  Auth::user()->id,
					'notifications_touser'		=>  $job->user_id,
					'notifications_value'		=>  Auth::user()->accounts->name . ' withdrawn your invitation to interview for the job "' . $job->headline . '".',
					'notification_ref'          =>  route('employer.jobs.mainaction.user', [$job->serial, Auth::user()->serial]),
					'notifications_type'		=> 'withdrawn_user'
				]); 
				return response()->json(['status' => 1 ,'msg' => "success"], 200);
				break;
			case 'proposal':
				$proposal =     Proposal::where('serial', $id)
									->where('user_id', Auth::user()->id)
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
				],[
					'decline_reference' => $proposal->serial,
					'decline_reason' 	=> $request->reason,
					'decline_user' 	 	=> Auth::user()->id, 
					'decline_note' 	 	=> $request->decline_notes,
					'other_reason' 	 	=> $request->other_reason
				]); 
				$proposal->update([
					'status' => 3
				]); 
				$job = $proposal->getJob();  
				Notification::create([
					'notifications_fromuser' 	=>  Auth::user()->id,
					'notifications_touser'		=>  $job->user_id,
					'notifications_value'		=>  Auth::user()->accounts->name . ' withdrawn the proposal for the job "' . $job->headline . '".',
					'notification_ref'          =>  route('employer.jobs.mainaction.user', [$job->serial, Auth::user()->serial]),
					'notifications_type'		=> 'withdrawn_user'
				]); 
				return response()->json(['status' => 1 ,'msg' => "success"], 200);
				break;
			case 'offer':
				$offer = 	Offer::where('serial', $id)
								->where('user_id', Auth::user()->id)
								->first(); 
				if(!isset($offer)){
					return response()->json(['status' => 0, 'msg' => "Invalid request"], 200);
				}  
				if($offer->status != 0){
					return response()->json(['status' => 1 ,'msg' => "This offer is taken already."], 200);
				}  
				Decline::updateOrCreate([
					'job_id' 			=> $offer->job_id,
					'user_id'			=> Auth::user()->id,
					'decline_type' 	 	=> 'offer',
				],[
					'decline_reference' => $offer->serial,
					'decline_reason' 	=> $request->reason,
					'decline_user' 	 	=> Auth::user()->id,
					'decline_note' 	 	=> $request->decline_notes,
					'other_reason' 	 	=> $request->other_reason
				]); 
				$offer->update([
					'status' => 3
				]);

				$job = $offer->getJob();   
				Notification::create([
					'notifications_fromuser' 	=>  Auth::user()->id,
					'notifications_touser'		=>  $job->user_id,
					'notifications_value'		=>  Auth::user()->accounts->name . ' decline the offer for the job "' . $job->headline . '".',
					'notification_ref'          =>  route('employer.jobs.mainaction.user', [$job->serial, Auth::user()->serial]),
					'notifications_type'		=> 'withdrawn_user'
				]); 
				
				/*
				$proposal 	= 	$offer->getProposal();
				if(isset($proposal)){
					$proposal->update([
						'status' => 3
					]);
				}
				*/

				return response()->json(['status' => 1 ,'msg' => "success"], 200);
				break;
		} 
		return response()->json(['status' => 0, 'msg' => "invalid request"], 200); 
	}
	public function changeterms(Request $request, $id){
		$proposal =     Proposal::where('serial', $id)
                            ->where('user_id', Auth::user()->id)
                            ->first(); 
        if(!isset($proposal))
			return response()->json(['status' => 0, 'msg' => "The proposal is not exist."], 200);
        
		$validator = Validator::make($request->all(),  [
			'proposal_amount'      => 'required',
			'specialized_role'     => 'required',
		]);
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);

		$role = Role::where('serial', $request->specialized_role)
					->where('user_id', Auth::user()->id)
					->where('is_deleted', 0)
					->first();
		if(!isset($role)){
			return response()->json(['status'=> 0, 'msg'=> "This role is not exist." ]);
		} 
		$proposal->update([
			'proposal_amount'   => $request->proposal_amount,
			'role'              => $role->id,
		]);
		Session::flash('message',  'The term is changed successfully.');	
		return response()->json(['status'=> 1, 'msg'=> "success."]); 
	}
	//offer action
	public function offer_details(Request $request, $id){
		$offer 	=   Offer::where('serial', $id)
						->where('user_id', Auth::user()->id) 
						->first();
        if(!isset($offer))
            abort(404);
		$this->offer =  $offer;
		return view('employee.jobs.offer_details', $this->data);
	}
	public function accept_offer(Request $request, $id){
		$offer 	=   Offer::where('serial', $id)
						->where('user_id', Auth::user()->id) 
						->first();
		if(!isset($offer))
			return response()->json(['status'=> 0, 'msg'=> "The offer is not exist"]);  
		if($offer->status != 0)
			return response()->json(['status'=> 0, 'msg'=> "The offer is taken already"]); 
		$validator = Validator::make($request->all(),  [
			'message_client'      => 'required'
		]);   		
		if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
		if(Auth::user()->accounts->loan_enable){
			if($request->getpaid_inadvance == "yes"){
				$validator = Validator::make($request->all(),  [
					'borrowing_amount'      => 'required'
				]);
				if($validator->fails())
					return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
				
				$lending_power          =   Auth::user()->accounts->loan_amount - Auth::user()->getLoanValue('total_loans_pending'); 
				$loan_fee 	            =	100 - Auth::user()->accounts->loan_fee; 
				$offer_loan_amount      =   $offer->EstimatedBudget() *  $loan_fee / 100; 
				$loan_amount            =   $offer_loan_amount;
				if( $offer_loan_amount >   $lending_power )
					$loan_amount = $lending_power;
				if($loan_amount < 0)
					$loan_amount = 0;
				
				if($loan_amount < $request->borrowing_amount )
					return response()->json(['status' => 0, 'msg' => "The request amount should be less than lending power."], 200);  
				Notification::create([
					'notifications_fromuser' 	=>  Auth::user()->id,
					'notifications_touser'		=>  0,
					'notifications_value'		=> 'The loan request $' . number_format( $request->borrowing_amount, 2)  . ' is sent to from ' . Auth::user()->accounts->name,
					'notifications_type'		=> 'loan_request'
				]); 
				LoanRequest::create([
					'amount'        => $request->borrowing_amount ,
					'user_id'       => Auth::user()->id,
					'offer_id'		=> $offer->id,
					'agreed_amount' => $request->borrowing_amount ,
					'status'        => 'pending'
				]);  
			} 
		}
		$job 			= 	$offer->getJob();
		$message_list 	=   $offer->getMessageList(); 
		if(!isset($message_list)){
			$message_list 	=  $offer->addMessageList();
		}
		Message::addMessage( Auth::user()->id, $offer->employer_id, $message_list->id, $request->message_client, 4, date('Y-m-d H:i:s'), $offer->id);
		$offer->update([
			'status' 		=> 1,
			'start_time'	=> date('Y-m-d H:i:s')
		]);
		// 1.if the client hired just one then the job will be closed 
		if(isset($job)){
			if($job->hire_more == '0')
				$job->update([
					'status' => 2
				]);
				$job->closeJob();
			//2. if the job is closed then the proposal should be changed
			$proposal = $job->getProposal($offer->user_id);
			if(isset($proposal)){
				$proposal->update([
					'status' => 20
				]);
			}
		}
		Notification::create([
			'notifications_fromuser' 	=>  0,
			'notifications_touser'		=>  Auth::user()->id,
			'notifications_value'		=>  'Your contract  "' .  $offer->contract_title . '" started.',
			'notification_ref'          =>  $offer->serial,
			'notifications_type'		=> 'offer_started'
		]);
		Notification::create([
			'notifications_fromuser' 	=>  Auth::user()->id,
			'notifications_touser'		=>  $offer->employer_id,
			'notifications_value'		=>  Auth::user()->accounts->name .  ' have accepted the contarct "' .  $offer->contract_title . '"  and started.',
			'notification_ref'          =>  $offer->serial,
			'notifications_type'		=> 'offer_accepted'
		]); 
		//Message::addMessage(Auth::user()->id, $freelancer->id, $message_list->id, $message_content, 3, $offer->created_at, $offer->id);
		$url = route('employee.jobs');
		Session::flash("message", "The contract is started successfully");
		return response()->json(['status' => 1, 'msg' => "The contract is started successfully", 'url' => $url]);
	}
}