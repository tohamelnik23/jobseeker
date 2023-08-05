<?php
namespace App\Model; 
use App\User;
use Carbon\Carbon; 
use Illuminate\Database\Eloquent\Model;
class Offer extends Model{
    protected $primaryKey 	= 'id';
	protected $table 		= 'offers'; 
    protected $fillable 	= ['job_id', 'employer_id' ,'payment_type','amount', 'user_id', 'work_details', 'offer_date', 'offer_time' ,'contract_title', 'status', 'serial', 'weekly_limit', 'start_time', 'end_time', 'minimum_hours'];
    // 0: pending, 1: hired and working,  2: paused  3: declined, 4: expired , 8:dispute  10: finished success fully. 11. finished unsuccess fully(almost cancelled)
    public static function boot(){
        parent::boot();
        self::creating(function($model){
            $model->status      =   0;
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
    public static function GenerateSerial($digit = 15){
        $chars 	= array(0,1,2,3,4,5,6,7,8,9);
        $max 	= count($chars) - 1;
        while(1){
            $sn = '';
            for($i = 0; $i < $digit; $i++)
                $sn .= $chars[rand(0, $max)];
            $apitoken = self::where('serial', $sn)->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;
    }
    public function getJob(){
        $job    =   Job::where('id', $this->job_id)
                            ->first();  
        return $job;
    }
    public function getProposal(){
        $proposal   =   Proposal::where('job_id', $this->job_id)
                                ->where('user_id', $this->user_id)
                                ->first(); 
        return $proposal;
    } 
    public function getProfile(){
        $job        = $this->getJob();
        $proposal   = $job->getProposal($this->user_id);
        if(isset($proposal))
            return $proposal->getProfile();
        $this->job_skills 	= 	$job->getSkills(1);  
        $user               =   User::where('serial', $this->user_id); 
        $profile_freelancer =   $user->getBestProfile($job_skills);  
        return $profile_freelancer;
    } 
    public function getFreelancer(){
        $freelancer =  User::where('id', $this->user_id)
                        ->first();
        return $freelancer;
    }
    public function getClient(){
        $freelancer =  User::where('id', $this->employer_id)
                        ->first();
        return $freelancer;
    }
    public static function getOffers($user_id){
        $offers     =   self::where('user_id', $user_id)
                            ->where('status', 0)
                            ->get();
        return $offers;
    } 
    public function getStatus(){
        $result = "unknown";
        switch($this->status){
            case '0': 
                $result = "Pending - expires on ". date('F d, Y'); 
                break;
            case '1':   $result     =   "Started"; break;
            case '2':   $result     =   "Paused"; break;
            case '3':   $result     =   "Declined "; 
                        $decline    =   Decline::where('declines.job_id', $this->job_id)
                                            ->where('declines.decline_user', $this->user_id) 
                                            ->first();
                        if(isset($decline)){
                            $result .= "  on " . date('F d, Y', strtotime($decline->created_at));
                        }
                break;
            case '4':   $result = "Expired"; break;
            case '5':   $result = "Pending"; break;
            case '6':   $result = "Pending"; break;
            case '7':   $result = "Pending"; break;
            case '10':  $result = "Finished"; break;
        }
        return $result;
    }   
    // client side
    public function getCurrentMilestone(){
        $current_milestone  =    Milestone::where(function ($query){
                                        $query->where('status',      'active') 
                                                ->orWhere('status',  'inactive');
                                    })
                                    ->where('offer_id', $this->id)
                                    ->orderBy('milestone_sort')
                                    ->first();
        return $current_milestone;
    }
    public function getUpcomiingMilestones(){
        $upcoming_milestones    =    Milestone::where(function ($query){
                                        $query->where('status',      'active') 
                                                ->orWhere('status',  'inactive');
                                    })
                                    ->where('offer_id', $this->id)
                                    ->orderBy('milestone_sort')
                                    ->get();
        $result = array();
        foreach($upcoming_milestones as $index => $upcoming_milestone){
            if($index){
                $result[] = $upcoming_milestone;
            }
        }
        return $result;
    } 
    //freelacner side
    public function getActiveMilestone(){
        $milestone      =   Milestone::where('status', 'active')
                                ->where('deposit_status', 1)
                                ->where('offer_id', $this->id)
                                ->first();
        return $milestone;
    } 
    public function getAwaitingMilestone(){
        $milestone      =   Milestone::where('status', 'inactive')
                                ->where('deposit_status', 0)
                                ->where('offer_id', $this->id)
                                ->first();
        return $milestone;
    }  
    public function getSubmitWork(){
        $submitwork =   SubmitWork::where('submit_work.status',  'active')
                                ->join('milestones', 'milestones.id', 'submit_work.milestone_id')
                                ->where('milestones.offer_id', $this->id)
                                ->first();
        return  $submitwork;
    }     
    public function getRemaininMilestones(){
        $milestones     =   Milestone::where('status', '<>' ,'approved') 
                                ->where('offer_id', $this->id)
                                ->orderBy('milestone_sort')
                                ->where('deleted', 0)
                                ->get();
        return $milestones;
    }       
    public function getCompletedMilestones(){
        $milestones     =   Milestone::where('status', 'approved') 
                                ->where('offer_id', $this->id)
                                ->orderBy('milestone_sort')
                                ->where('deleted', 0)
                                ->get();
        return $milestones;
    }
    public function getTotalPaid(){ 
        // Processing Fee 
        $total_out      =   DiscovergigTransaction::where('direction', 'in')
                                                ->where('offer_id', $this->id) 
                                                ->sum('amount');
        
        $total_out  =   round($total_out, 2);
        return $total_out;
    }
    public function getMilestonePaid(){
        $total_milestones_paid  =   Milestone::where('status', 'approved') 
                                        ->where('offer_id', $this->id) 
                                        ->where('deleted', 0)
                                        ->sum('paid_amount');
        return  $total_milestones_paid;
    }       
    public function getTotalRemaing(){
        $total_paid     =  $this->getMilestonePaid();
        $total_remaing  =  $this->amount - $total_paid;
        if($total_remaing < 0)
            $total_remaing = 0;
        
        $total_remaing  =   round($total_remaing, 2);
        return $total_remaing;
    }
    public function getTotalEscrow(){
        $total_in   =   Escrow::where('status', 'available')
                                ->where('direction', 'in')
                                ->where('offer_id', $this->id)                                                       
                                ->sum('amount');
        $total_out  =   Escrow::where('status', 'available')
                                ->where('direction', 'out')
                                ->where('offer_id', $this->id)
                                ->sum('amount');
        $total  =   $total_in - $total_out;
        $total  =   round($total, 2);
        return $total;
    } 
    public function getAdditionalEarnings(){
        $addtionalEarnings      =   ['Service Fee', 'Bonus', 'Expense reimbursement']; 
        $freelancer_history     =   DiscovergigTransaction::where('user_id', $this->user_id)
                                                ->whereIn('type', $addtionalEarnings)
                                                ->where('offer_id', $this->id)
                                                ->orderBy('id', 'desc')
                                                ->get();
        return  $freelancer_history;
    } 
    public function getAllTimesheets(){
        $addtionalEarnings      =   ['Service Fee', 'Hourly']; 
        $freelancer_history     =   DiscovergigTransaction::where('user_id', $this->user_id)
                                                ->whereIn('type', $addtionalEarnings)
                                                ->where('offer_id', $this->id)
                                                ->orderBy('id', 'desc')
                                                ->get();
        return  $freelancer_history;
    } 
    public function getAllEscrowTimesheets(){
        $addtionalEarnings      =   ['Processing Fee', 'Payment', 'Bonus', 'Expense reimbursement']; 
        $freelancer_history     =   Escrow::where('user_id', $this->employer_id)
                                            ->whereIn('type', $addtionalEarnings)
                                            ->where('offer_id', $this->id)
                                            ->orderBy('id', 'desc')
                                            ->get();
        return  $freelancer_history;
    }
    public function getAdditionalPayments(){
        $addtionalEarnings      =   ['Processing Fee', 'Bonus', 'Expense reimbursement']; 
        $freelancer_history     =   Escrow::where('user_id', $this->employer_id)
                                                ->whereIn('type', $addtionalEarnings)
                                                ->where('offer_id', $this->id)
                                                ->orderBy('id', 'desc')
                                                ->get();
        return  $freelancer_history;
    }   
    public function getAllTimesheetsPayments(){
        $addtionalEarnings      =   ['Processing Fee', 'Bonus', 'Hourly', 'Expense reimbursement']; 
        $freelancer_history     =   Escrow::where('user_id', $this->user_id)
                                                ->whereIn('type', $addtionalEarnings)
                                                ->where('offer_id', $this->id)
                                                ->orderBy('id', 'desc')
                                                ->get();
        return  $freelancer_history;
    }  
    public function getFeedback($user_id){
        if($this->status != 10) return null;
        $feedback   =   Feedback::where('user_id', $user_id)
                            ->where('offer_id', $this->id)
                            ->first();
        return $feedback;
    }
    public static function addMinuteTimes($results){
        $minutes = 0;
        foreach($results as $result){
            list($hour, $minute) = explode(':', $result->timesheets_time );
            $minutes            += $hour * 60;
            $minutes            += $minute;
        }
        $hours      =   floor($minutes / 60);
        $minutes    -=  $hours * 60; 
        $result     =   sprintf('%02d:%02d', $hours, $minutes);
        return $result;
    }     
    // Hourly Feature   
    public function totalTimeHours($type){
        $result =  "00:00";
        $now    =  Carbon::now(); 
        switch($type){
            case 'last_24':
                $results    =   TimeSheet::where('offer_id',  $this->id)
                                    ->where('timesheets_date', '>=' , Carbon::now()->subDay()->toDateString())
                                    ->where('timesheets_date', '<=' , Carbon::now()->toDateString())
                                    ->get();                
                $result     =   self::addMinuteTimes($results);
                break;
            case 'this_week':
                $results    =   TimeSheet::where('offer_id',  $this->id)
                                    ->where('timesheets_date', '>=' , $now->startOfWeek(Carbon::MONDAY)->toDateString())
                                    ->where('timesheets_date', '<=' , Carbon::now()->toDateString())
                                    ->get(); 
                $result =   self::addMinuteTimes($results);
                break;
            case 'last_week': 
                $results    =   TimeSheet::where('offer_id',  $this->id)
                                    ->where('timesheets_date', '>=' , $now->subDays(7)->startOfWeek(Carbon::MONDAY)->toDateString())
                                    ->where('timesheets_date', '<=' , Carbon::now()->subDays(7)->endOfWeek(Carbon::SUNDAY)->toDateString())
                                    ->get();
                $result =   self::addMinuteTimes($results);
                break;
            case 'since_start':
                $results    =   TimeSheet::where('offer_id',  $this->id) 
                                    ->get(); 
                $result =   self::addMinuteTimes($results);
                break;
        }
        return $result;
    } 
    public function getTimeSheet($start_date){
        $result         =   array();  
		// result
		$prev_week      =   Carbon::createFromFormat('Y-m-d', $start_date)->subDays(7)->startOfWeek(Carbon::MONDAY)->toDateString();
		$next_week      =   Carbon::createFromFormat('Y-m-d', $start_date)->addDays(7)->startOfWeek(Carbon::MONDAY)->toDateString();       
		//current
		$curr_week      =   Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
		//start_week
		$start_week     =   Carbon::createFromFormat('Y-m-d',  date('Y-m-d', strtotime( $this->start_time ))) ->startOfWeek(Carbon::MONDAY)->toDateString();
        
		if($prev_week < $start_week)
			$prev_week  =   ""; 
        
		if($next_week > $curr_week)
			$next_week  =   "";
        
        $result['next_week']    =   $next_week;
        $result['prev_week']    =   $prev_week;
        $result['curr_week']    =   $curr_week;
        $result['first_week']   =   Carbon::createFromFormat('Y-m-d', $start_date)->startOfWeek(Carbon::MONDAY)->toDateString(); 
        return $result;
    } 
    public function getTimeSheets($start_date){
        $result     =   array();
        $curr_week 	= 	Carbon::createFromFormat('Y-m-d', $start_date)->startOfWeek(Carbon::MONDAY);
        for($i = 0; $i < 7; $i++){
            $current_item 				    = 	array();
            $current_item['time_sheet']	    =   TimeSheet::where('offer_id', $this->id)
                                                    ->where('timesheets_date', $curr_week->toDateString())
                                                    ->first(); 
            $result[]                       =   $current_item;
            $curr_week->addDay();
        } 
        return $result;
    } 
    public function getLastTimeSheet(){
        $timesheet =   TimeSheet::where('offer_id',  $this->id)
                            ->orderBy("timesheets_date", 'desc')
                            ->where('timesheets_time', '<>' , '00:00:00')
                            ->first();
        return $timesheet;        
    } 
    public function getLastWeekPaid(){
        $star_of_week 	 = 	Carbon::now()->subDays(7)->startOfWeek(Carbon::MONDAY)->toDateString();	
		$end_of_week 	 =	Carbon::now()->subDays(7)->endOfWeek(Carbon::SUNDAY)->toDateString();  
        $timesheets  	= 	TimeSheet::where('offer_id', $this->id)
                                ->where('timesheets_date',	'>=', 	$star_of_week)
                                ->where('timesheets_date', '<=' , 	$end_of_week)
                                ->join("offers", "offers.id", "timesheets.offer_id") 
                                ->select('timesheets.*', 'offers.amount') 
                                ->get();   
        $minutes    	= 	0;
        foreach($timesheets as $timesheet){
            list($hour, $minute)    =   explode(':', $timesheet->timesheets_time );
            $minutes                +=  $hour * 60;
            $minutes                +=  $minute;    
        }

        $hours_val   =  $minutes / 60;
        $hours_val   =  round($hours_val, 2); 

        if(isset($timesheet))
            $total_paid  =  $hours_val * $timesheet->amount;
        else
            $total_paid  =  0;
        return $total_paid;
    } 
    public function getLastWeekTotalPaid(){
        $timesheets  	= 	TimeSheet::where('offer_id', $this->id) 
                                ->join("offers", "offers.id", "timesheets.offer_id") 
                                ->select('timesheets.*', 'offers.amount') 
                                ->get();   
        $minutes    	= 	0;
        foreach($timesheets as $timesheet){
            list($hour, $minute)    =   explode(':', $timesheet->timesheets_time );
            $minutes                +=  $hour * 60;
            $minutes                +=  $minute;    
        } 
        $hours_val   =  $minutes / 60;
        $hours_val   =  round($hours_val, 2); 
        if(isset($timesheet))
            $total_paid  =  $hours_val * $timesheet->amount;
        else
            $total_paid  =  0;
            
        return $total_paid;
    }  
    // weekday = 0, this week,  1: last week, etc
    public function manualEscrowReleaseForTimeSheet( $weekday = 0 ){
        $past_days       =   $weekday * 7;
        $star_of_week 	 = 	Carbon::now()->subDays($past_days)->startOfWeek(Carbon::MONDAY)->toDateString();	
		$end_of_week 	 =	Carbon::now()->subDays($past_days)->endOfWeek(Carbon::SUNDAY)->toDateString(); 
		$timesheet_label = 	Carbon::now()->subDays($past_days)->startOfWeek(Carbon::MONDAY)->format('m/d/Y') . '-' . Carbon::now()->subDays(7)->endOfWeek(Carbon::SUNDAY)->format('m/d/Y'); 
        //////////////////////////////////////////////// ESCROW //////////////////////////////////////////////
        $timesheets  	= 	TimeSheet::where('offer_id', $this->id)
                                ->where('timesheets_date',	'>=', 	$star_of_week)
                                ->where('timesheets_date', '<=' , 	$end_of_week)
                                ->join("offers", "offers.id", "timesheets.offer_id") 
                                ->select('timesheets.*', 'offers.amount')
                                ->where("deposit_status", 0)
                                ->get();
        if(count($timesheets)){
            $minutes    	= 	0;
            foreach($timesheets as $timesheet){
                list($hour, $minute)    =   explode(':', $timesheet->timesheets_time );
                $minutes                +=  $hour * 60;
                $minutes                +=  $minute;    
            }
            $hours_val   =  $minutes / 60;
            $hours_val   =  round($hours_val, 2); 
            $total_paid  =  $hours_val * $timesheet->amount;            
            $hours       =  floor($minutes / 60);
            $minutes    -=  $hours * 60;
            if($total_paid){
                $job_poster_fee =   MasterSetting::getValue('job_poster_fee');
                $escrow_fee     =   100 - $job_poster_fee;
                $offer_amount   =   round($total_paid * 100 / $escrow_fee, 2);
                $service_fee  	=   round($offer_amount -  $total_paid, 2); 
                $cards 			= 	Card::where('user_id', $this->employer_id)
                                        ->where('status', 'verified')
                                        ->get();
                if(!count($cards)){
                    $offer->update([
                        'status' => 2
                    ]);
                    return -1;
                } 
                $flag = 0;
                foreach($cards as $card){
                    $sendData 			= 	array();
                    $sendData['refId']	= 	$card->serial;
                    $pay_result   		=   $card->payOrderWithProfile($offer_amount, $sendData); 
                    if($pay_result['status']){
                        $transaction	          = Transaction::create([
                            'amount'        =>  $offer_amount,
                            'status'        => 'success',
                            'type'          => 'charge',
                            'card_id'       =>  $card->id,
                            'user_id'       =>  $this->employer_id,
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
                            continue;
                        }
                        $flag = 1;
                        break;
                    }
                    else{
                        $error_msg = "There was a system problem.  Please retry in a few minutes."; 
                    }
                }
                if(!$flag){
                    // notification 
                    $this->update([
                        'status' => 2
                    ]);
                    return -1;
                }
                else{    
                    //charge amount
                    $escrow 	     = 	Escrow::create([
                        'amount' 	 => $offer_amount,
                        'ref_id' 	 => $transaction->id,
                        'offer_id'	 => $this->id,
                        'status'	 => 'available',
                        'type'	 	 => "Payment",
                        'description'=> "Paid for  " . $card->card_type . ' ' . $timesheet_label .  ' - ' . sprintf('%02d:%02d', $hours, $minutes) . " hrs @ $" .  number_format($timesheet->amount, 2) . "/hr", 
                        'direction'	 => 'in',
                        'user_id'	 => $this->employer_id
                    ]);    
                    $escrow 	= 	Escrow::create([
                                        'amount' 	 => $service_fee,
                                        'ref_id' 	 => $transaction->id,
                                        'offer_id'	 => $this->id,
                                        'status'	 => 'available',
                                        'type'	 	 => "Processing Fee",
                                        'description'=> "Payment Processing Fee", 
                                        'direction'	 => 'out',
                                        'user_id'	 => $this->employer_id
                                    ]);
                    TimeSheet::where('offer_id', $this->id)
                            ->where('timesheets_date',	'>=', 	$star_of_week)
                            ->where('timesheets_date', '<=' , 	$end_of_week) 
                            ->update([
                                "deposit_status"  => 1
                            ]); 
                    Notification::create([
                        'notifications_fromuser' 	=>  0,
                        'notifications_touser'		=>  $this->employer_id,
                        'notifications_value'		=>  'The escrow is funded for ' . $card->card_type . ' ' . $timesheet_label .  ' - ' . sprintf('%02d:%02d', $hours, $minutes) . " hrs @ $" .  number_format($timesheet->amount, 2) . "/hr",
                        'notification_ref'          =>  route('employer.reports.billing_history'),
                        'notifications_type'		=> 'escrow_requested'
                    ]); 
                }
            }
        } 
        //////////////////////////////////////////////// RELEASE //////////////////////////////////////////////
        $timesheets  	= 	TimeSheet::where('offer_id', $this->id)
									->where('timesheets_date',	'>=', 	$star_of_week)
									->where('timesheets_date', '<=' , 	$end_of_week)
									->join("offers", "offers.id", "timesheets.offer_id") 
									->select('timesheets.*', 'offers.amount')
									->where("deposit_status", 1)
									->get(); 
        if(count($timesheets)){
            $minutes    	= 	0;
            foreach($timesheets as $timesheet){
                list($hour, $minute)    =   explode(':', $timesheet->timesheets_time );
                $minutes                +=  $hour * 60;
                $minutes                +=  $minute;    
            }  
            $hours_val   =  $minutes / 60;
            $hours_val   =  round($hours_val, 2); 
            $total_paid  =  $hours_val * $timesheet->amount;
            
            $hours       =  floor($minutes / 60);
            $minutes    -=  $hours * 60;
            if($total_paid){
                $job_taker_fee 				=   MasterSetting::getValue('job_taker_fee');
                $service_fee_main			=   number_format( $total_paid * $job_taker_fee / 100, 2);
                $discovergig_transaction 	=	DiscovergigTransaction::create([
                                                    'amount' 		=>  $total_paid,
                                                    'type'	 		=> "Hourly",
                                                    'description'	=> "Invoice for " .   $timesheet_label .  ' - ' . sprintf('%02d:%02d', $hours, $minutes) . " hrs @ $" .  number_format($timesheet->amount, 2) . "/hr",
                                                    'direction'		=> 'in',
                                                    'user_id'		=>  $this->user_id,
                                                    'offer_id'		=>  $this->id, 
                                                    'status'		=> 'pending'
                                                ]);
                $discovergig_transaction 	=	DiscovergigTransaction::create([
                                                    'amount' 		=> $service_fee_main,
                                                    'type'	 		=> "Service Fee",
                                                    'description'	=> "Service Fee for Hourly - Ref ID " .  $discovergig_transaction->serial,
                                                    'direction'		=> 'out',
                                                    'user_id'		=>  $this->user_id, 
                                                    'offer_id'		=>  $this->id,
                                                    'status'		=> 'pending'
                                                ]);
                $escrow 					= 	Escrow::create([
                                                    'amount' 		=> $total_paid,													
                                                    'offer_id'		=> $this->id,
                                                    'status'		=> 'available',
                                                    'type'	 		=> "Hourly",
                                                    'direction'		=> 'out',
                                                    'description' 	=> "Invoice from escrow for " .  $timesheet_label .  ' - ' . sprintf('%02d:%02d', $hours, $minutes) . " hrs @ $" .  number_format($timesheet->amount, 2) . "/hr",
                                                    'user_id'		=>  $this->employer_id
                                                ]);
                TimeSheet::where('offer_id', $this->id)
                            ->where('timesheets_date',	'>=', 	$star_of_week)
                            ->where('timesheets_date', '<=' , 	$end_of_week) 
                            ->update([
                                "deposit_status"  => 2
                            ]);
            }    
        }
        return 0; 
    }
    public function EstimatedBudget(){
        if($this->payment_type == "fixed")
            return $this->amount;
        $payment_amount =  round( $this->amount * $this->minimum_hours, 2 );
        return $payment_amount;
    }
    public function getMessageList($status = 0){
        $job            =   $this->getJob();
        $messageList    =   null;
        if(isset($job)){
            $messageList = $job->getMessageList( $this->user_id );  
        }
        else{
            $messageList    =   MessageList::where('job_id', $this->id)
                                    ->where('to_user_id', $this->user_id)
                                    ->where('status', $status)
                                    ->where('type', 'offer')
                                    ->first();    
        }
        return $messageList;
    }
    public function addMessageList(){
        $job            =   $this->getJob();
        if(isset($job)){
            $message_list 		= 	MessageList::where('job_id', $job->id)
                                        ->where('to_user_id', $this->user_id)
                                        ->where('type', 'job')
                                        ->first(); 
            if(!isset($message_list))
                $message_list 	=  MessageList::addMessageList($this->employer_id, $this->user_id, $job->id);  	
        }else{
            $message_list 		= 	MessageList::where('job_id', $this->id)
                                        ->where('to_user_id', $this->user_id)
                                        ->where('type', 'offer')
                                        ->first(); 
            if(!isset($message_list))
                $message_list 	=  MessageList::addMessageList($this->employer_id, $this->user_id, $offer->id, 'offer');
        }
    }                                                            
}