<?php 
namespace App\Http\Controllers\master;
use App\Http\Controllers\BaseController;
use App\User;
use Auth;
use Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Question; 
use App\Model\MasterSetting; 
use App\Model\SlackInfo;
class SettingsController extends BaseController {
    public function index(Request $request){ 
        //general setting
        $this->job_poster_fee                       =   MasterSetting::getValue('job_poster_fee'); 
        $this->job_taker_fee                        =   MasterSetting::getValue('job_taker_fee'); 
        $this->expire_date                          =   MasterSetting::getValue('expire_date'); 
        $this->expire_warning_date                  =   MasterSetting::getValue('expire_warning_date');
        $this->expire_interview_date                =   MasterSetting::getValue('expire_interview_date');
        $this->expire_offer_date                    =   MasterSetting::getValue('expire_offer_date');
        $this->verify_request_warning_date          =   MasterSetting::getValue('verify_request_warning_date');
        //payment type
        $this->live_transactionkey      =   MasterSetting::getValue('live_transactionkey');
        return view('master.settings.index', $this->data);
    }
    public function update(Request $request){
        if($request->type == "global"){
            $validator     = Validator::make($request->all(), array(
                'job_poster_fee'                    => 'required | max:100 | min: 0',
                'job_taker_fee'                     => 'required | max:100 | min: 0', 
                'expire_date'                       => 'required | max:1000 | min: 1',
                'expire_warning_date'               => 'required | max:100 | min: 1',
                'expire_interview_date'             => 'required | max:100 | min: 1',
                'expire_offer_date'                 => 'required | max:100 | min: 1',
                'verify_request_warning_date'       => 'required | max:100 | min: 1',
            ));                                                                 
            if($validator->fails()){
                return redirect()->back()->withInput()->withErrors($validator);
            } 
            MasterSetting::addValue('job_poster_fee',                       $request->job_poster_fee);
            MasterSetting::addValue('job_taker_fee',                        $request->job_taker_fee);
            MasterSetting::addValue('expire_date',                          $request->expire_date);
            MasterSetting::addValue('expire_warning_date',                  $request->expire_warning_date);
            MasterSetting::addValue('expire_interview_date',                $request->expire_interview_date);
            MasterSetting::addValue('expire_offer_date',                    $request->expire_offer_date);
            MasterSetting::addValue('verify_request_warning_date',          $request->verify_request_warning_date);
            return redirect()->back()->with('message', "The main info is updated successfully.");   
        }
        
        if($request->type == "payment"){
            $validator     = Validator::make($request->all(), array(
                'live_transactionkey'        => 'required | max:100'
            ));
            if($validator->fails()){
                return redirect()->back()->withInput()->withErrors($validator);
            }
            MasterSetting::addValue('live_transactionkey', $request->live_transactionkey); 
            Session::flash('setting_update_type', $request->type);
            return redirect()->back()->with('message', "Payment details have been updated.");   
        } 
        return redirect()->back()->with('error', "You have not updated anything.");   
    } 
}
