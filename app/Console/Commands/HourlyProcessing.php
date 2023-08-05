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
class HourlyProcessing extends Command{
    protected $description 	= 'This is for hourly job.';
    protected $signature 	= 'payment:hourly'; 
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
									->where("deposit_status", 1)
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
									->where("deposit_status", 1)
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
			// paid 
			$firday_week 	= 	Carbon::now()->startOfWeek(Carbon::FRIDAY)->toDateString();
			if(Carbon::now()->toDateString() == $firday_week){ 
				if($total_paid){
					$job_taker_fee 				=   MasterSetting::getValue('job_taker_fee');
					$service_fee_main			=   number_format( $total_paid * $job_taker_fee / 100, 2);
					$discovergig_transaction 	=	DiscovergigTransaction::create([
														'amount' 		=>  $total_paid,
														'type'	 		=> "Hourly",
														'description'	=> "Invoice for " .   $timesheet_label .  ' - ' . sprintf('%02d:%02d', $hours, $minutes) . " hrs @ $" .  number_format($timesheet->amount, 2) . "/hr",
														'direction'		=> 'in',
														'user_id'		=>  $offer->user_id,
														'offer_id'		=>  $offer->id, 
														'status'		=> 'pending'
													]);
					$discovergig_transaction 	=	DiscovergigTransaction::create([
														'amount' 		=> $service_fee_main,
														'ty	pe'	 		=> "Service Fee",
														'description'	=> "Service Fee for Hourly - Ref ID " .  $discovergig_transaction->serial,
														'direction'		=> 'out',
														'user_id'		=>  $offer->user_id, 
														'offer_id'		=>  $offer->id,
														'status'		=> 'pending'
													]);
					$escrow 					= 	Escrow::create([
														'amount' 		=> $total_paid,													
														'offer_id'		=> $offer->id,
														'status'		=> 'available',
														'type'	 		=> "Hourly",
														'direction'		=> 'out',
														'description' 	=> "Invoice from escrow for " .  $timesheet_label .  ' - ' . sprintf('%02d:%02d', $hours, $minutes) . " hrs @ $" .  number_format($timesheet->amount, 2) . "/hr",
														'user_id'		=>  $offer->employer_id
													]);
					TimeSheet::where('offer_id', $hourly_id->offer_id)
								->where('timesheets_date',	'>=', 	$star_of_week)
								->where('timesheets_date', '<=' , 	$end_of_week) 
								->update([
									"deposit_status"  => 2
								]);
				}
			}
		}
    }
}