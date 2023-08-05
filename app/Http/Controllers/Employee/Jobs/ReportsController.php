<?php
namespace App\Http\Controllers\Employee\Jobs;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Session;
use Auth;
use Config, Mail, DB;
use Carbon\Carbon; 
use App\Model\DiscovergigTransaction;
use App\Model\TimeSheet;
use App\Model\Offer;
use App\Model\SubmitWork;
use App\Helpers\Mainhelper;
use Illuminate\Support\Facades\Validator;
class ReportsController extends BaseController{
    public function __construct(){
		$this->middleware(array('auth','employee'));
    } 
    public function reports_action(Request $request, $main_request){
        $suggested_url = ['in-progress', 'in-review', 'pending', 'available']; 
        if(!in_array( $main_request, $suggested_url)){
			abort( 404 );
		}
        switch($main_request){
            case 'pending':
                $fixed_prices           =   DiscovergigTransaction::where('user_id', Auth::user()->id)
                                                        ->where('status', 'pending')       
                                                        ->where('type', 'Fixed Price')
                                                        ->orderBy("created_at", "desc")
                                                        ->get();
                $this->fixed_prices =   $fixed_prices;

                $hourly_prices          =   DiscovergigTransaction::where('user_id', Auth::user()->id)
                                            ->where('status', 'pending')       
                                            ->where('type', 'Hourly')
                                            ->orderBy("created_at", "desc")
                                            ->get();
                $this->hourly_prices    =   $hourly_prices;
                 
                $other_payments     =   DiscovergigTransaction::where('user_id', Auth::user()->id)
                                            ->where('status', 'pending')       
                                            ->where(function ($query){
                                                $query->where('type',      "Service Fee") 
                                                        ->orWhere('type',    "Bonus");
                                            })
                                            ->orderBy("created_at", "desc")
                                            ->get();
                $this->other_payments   =   $other_payments;
                break;
            case 'available':
                $now =  Carbon::now()->subDays(30);
                $this->payment_reports  =    DiscovergigTransaction::where('user_id', Auth::user()->id)
                                                ->orderBy("status")
                                                ->orderBy("id", "desc")
                                                ->where('updated_at', '>=', $now->toDateTimeString())
                                                ->where('status', 'available')       
                                                ->get();
                break;
            case 'in-progress':
                $star_of_week 	= 	Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
                $hourly_ids 	= 	TimeSheet::where('timesheets_date', '>=', $star_of_week)
                                            ->join("offers", "offers.id", "timesheets.offer_id") 
                                            ->select('timesheets.offer_id')
                                            ->where('offers.user_id',  Auth::user()->id)
                                            ->groupBy('timesheets.offer_id')
                                            ->get();
                $hourly_offers  =   array();
                foreach($hourly_ids as $hourly_id){
                    $hourly_offer   =   Offer::where('id', $hourly_id->offer_id)
                                                ->first();
                    if(isset($hourly_offer))
                        $hourly_offers[] =  $hourly_offer;
                }
                $this->hourly_offers    =  $hourly_offers;
                $this->timelist         =  Carbon::now()->startOfWeek(Carbon::MONDAY)->format('M d') . ' - ' . Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('M d');

                $curr_week 	=   Carbon::now()->startOfWeek(Carbon::MONDAY);
                $result 	= 	array();
                for($i = 0; $i < 7; $i++){
                    $current_item 				= 	array();
                    $current_item['date']		=	$curr_week->format('M d');
                    $current_item['week']		=	Mainhelper::getWeekName($curr_week->format('w'));
                    $result[] 					=	$current_item;
                    $curr_week->addDay();
                } 
                $this->weeksheets 	= 	$result;
                $this->curr_date    =   Carbon::now()->toDateString(); 
                break;
            case 'in-review':
                $star_of_week 	= 	Carbon::now()->subDays(7)->startOfWeek(Carbon::MONDAY)->toDateString(); 
                $hourly_ids 	= 	TimeSheet::where('timesheets_date', '>=', $star_of_week)
                                            ->join("offers", "offers.id", "timesheets.offer_id") 
                                            ->select('timesheets.offer_id')
                                            ->where('offers.user_id',  Auth::user()->id)
                                            ->groupBy('timesheets.offer_id')
                                            ->where('deposit_status', '<>' ,'2')
                                            ->get();
                $hourly_offers  =   array();
                foreach($hourly_ids as $hourly_id){
                    $hourly_offer   =   Offer::where('id', $hourly_id->offer_id)
                                                ->first();
                    if(isset($hourly_offer))
                        $hourly_offers[] =  $hourly_offer;
                }
                $this->hourly_offers    =  $hourly_offers;
                $this->timelist         =  Carbon::now()->subDays(7)
                                                        ->startOfWeek(Carbon::MONDAY)->format('M d') . ' - ' . Carbon::now()->subDays(7)
                                                        ->endOfWeek(Carbon::SUNDAY)->format('M d');
                $curr_week 	            =   Carbon::now()->subDays(7)->startOfWeek(Carbon::MONDAY);
                $result 	            = 	array();
                for($i = 0; $i < 7; $i++){
                    $current_item 				= 	array();
                    $current_item['date']		=	$curr_week->format('M d');
                    $current_item['week']		=	Mainhelper::getWeekName($curr_week->format('w'));
                    $result[] 					=	$current_item;
                    $curr_week->addDay();
                }
                $this->weeksheets 	= 	$result;  
                // milestone
                $submit_works =     SubmitWork::where('submit_work.user_id', Auth::user()->id) 
                                        ->where('submit_work.status',  'active')
                                        ->leftJoin("milestones", "milestones.id", "submit_work.milestone_id")
                                        ->leftJoin("offers", "offers.id", "milestones.offer_id")
                                        ->leftJoin("jobs", "jobs.id", "offers.job_id")
                                        ->select("submit_work.*", "milestones.headline", "jobs.headline as job_title", "offers.serial as offer_serial")
                                        ->get();
                //dd( $submit_works  );
                $this->submit_works     =   $submit_works;
                $this->curr_date        =   Carbon::now()->subDays(7)->toDateString();
                break;
        }
        $this->main_request 	= 	$main_request;
        return view('employee.reports.home_page', $this->data);
    }

    public function earnings_history(Request $request){ 
        // $now =  Carbon::now()->subDays(30);
        $this->payment_reports  =    DiscovergigTransaction::where('user_id', Auth::user()->id)
                                        ->orderBy("status")
                                        ->orderBy("id", "desc")
        //                                ->where('updated_at', '>=', $now->toDateTimeString()) 
                                        ->get();
        return view('employee.reports.reports', $this->data);
    }
}