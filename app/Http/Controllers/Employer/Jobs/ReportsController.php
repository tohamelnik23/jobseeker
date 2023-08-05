<?php
namespace App\Http\Controllers\Employer\Jobs;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Session;
use Auth;
use Config, Mail, DB;
use Carbon\Carbon; 
use App\Model\Escrow;
use Illuminate\Support\Facades\Validator;
class ReportsController extends BaseController{
    public function __construct(){
		$this->middleware(array('auth','employer'));
    }  
    public function billing_history(Request $request){
          // $now =  Carbon::now()->subDays(30);
        $this->payment_reports  =   Escrow::where('user_id', Auth::user()->id)
                                        ->orderBy("id", "desc")
                                        //->where('updated_at', '>=', $now->toDateTimeString()) 
                                        ->get();
        return view('employer.reports.reports', $this->data);
    } 
}