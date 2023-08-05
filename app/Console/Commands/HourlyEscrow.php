<?php 
namespace App\Console\Commands; 
use Illuminate\Console\Command;
use Config, Mail, DB;
use Carbon\Carbon; 
use App\Model\DiscovergigTransaction;
use App\Model\Escrow;
use App\Model\TimeSheet;
use App\Model\MasterSetting;
use App\Model\Card;
use App\Model\Transaction;
use App\Model\Offer;
use App\Model\Notification;
class HourlyEscrow extends Command{
    protected $description 	= 'This is for hourly job.';
    protected $signature 	= 'payment:escrow'; 
    public function __construct(){
        parent::__construct();
    }
    public function handle(){ 
        $star_of_week 	 = 	Carbon::now()->subDays(7)->startOfWeek(Carbon::MONDAY)->toDateString();	
		$end_of_week 	 =	Carbon::now()->subDays(7)->endOfWeek(Carbon::SUNDAY)->toDateString(); 
		$timesheet_label = 	Carbon::now()->subDays(7)->startOfWeek(Carbon::MONDAY)->format('m/d/Y') . '-' . Carbon::now()->subDays(7)->endOfWeek(Carbon::SUNDAY)->format('m/d/Y');
		$hourly_ids 	= 	TimeSheet::where('timesheets_date', '>=', $star_of_week)
									->where('timesheets_date', '<=' , $end_of_week)
									->join("offers", "offers.id", "timesheets.offer_id") 
									->select('timesheets.offer_id')
									->where("deposit_status", 0)
									->groupBy("offer_id")
									->get(); 
		foreach($hourly_ids as $hourly_id){
			$offer 			= 	Offer::where('id',  $hourly_id->offer_id)
											->first();
			if(!isset($offer))
				continue;
			$timesheets  	= 	TimeSheet::where('offer_id', $hourly_id->offer_id)
									->where('timesheets_date',	'>=', 	$star_of_week)
									->where('timesheets_date', '<=' , 	$end_of_week)
									->join("offers", "offers.id", "timesheets.offer_id") 
									->select('timesheets.*', 'offers.amount')
									->where("deposit_status", 0)
									->get(); 
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
			// escrow
			$monday_week 	= 	Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
			if(Carbon::now()->toDateString() == $monday_week){
				if($total_paid){
					$job_poster_fee =   MasterSetting::getValue('job_poster_fee');
					$escrow_fee     =   100 - $job_poster_fee;
					$offer_amount   =   round($total_paid * 100 / $escrow_fee, 2);
					$service_fee  	=   round($offer_amount -  $total_paid, 2); 
					$cards 			= 	Card::where('user_id', $offer->employer_id)
											->where('status', 'verified')
											->get();
					if(!count($cards)){
						$offer->update([
							'status' => 2
						]);
						continue;
					}
					$flag = 0;
					foreach($cards as $card){ 
						$sendData 			= 	array();
						$sendData['refId']	= 	$card->serial;
						$pay_result   		=   $card->payOrderWithProfile($offer_amount, $sendData); 
						if($pay_result['status']){
							$transaction	 = Transaction::create([
								'amount'        =>  $offer_amount,
								'status'        => 'success',
								'type'          => 'charge',
								'card_id'       =>  $card->id,
								'user_id'       =>  $offer->employer_id,
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
						$offer->update([
							'status' => 2
						]);
					}
					else{
						//charge amount
						$escrow 	= 	Escrow::create([
							'amount' 	 => $offer_amount,
							'ref_id' 	 => $transaction->id,
							'offer_id'	 => $offer->id,
							'status'	 => 'available',
							'type'	 	 => "Payment",
							'description'=> "Paid for  " . $card->card_type . ' ' . $timesheet_label .  ' - ' . sprintf('%02d:%02d', $hours, $minutes) . " hrs @ $" .  number_format($timesheet->amount, 2) . "/hr", 
							'direction'	 => 'in',
							'user_id'	 => $offer->employer_id
						]); 
						$escrow 	= 	Escrow::create([
											'amount' 	 => $service_fee,
											'ref_id' 	 => $transaction->id,
											'offer_id'	 => $offer->id,
											'status'	 => 'available',
											'type'	 	 => "Processing Fee",
											'description'=> "Payment Processing Fee", 
											'direction'	 => 'out',
											'user_id'	 => $offer->employer_id
										]);
						TimeSheet::where('offer_id', $hourly_id->offer_id)
								->where('timesheets_date',	'>=', 	$star_of_week)
								->where('timesheets_date', '<=' , 	$end_of_week) 
								->update([
									"deposit_status"  => 1
								]);
						
						Notification::create([
							'notifications_fromuser' 	=>  0,
							'notifications_touser'		=>  $offer->employer_id,
							'notifications_value'		=>  'The escrow is funded for ' . $card->card_type . ' ' . $timesheet_label .  ' - ' . sprintf('%02d:%02d', $hours, $minutes) . " hrs @ $" .  number_format($timesheet->amount, 2) . "/hr",
							'notification_ref'          =>  route('employer.reports.billing_history'),
							'notifications_type'		=> 'escrow_requested'
						]);

					}

				}
			}
		}
    }
	
}