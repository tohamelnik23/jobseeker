<?php
namespace App\Http\Controllers\Employee\Jobs;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Session;
use Auth;
use Config, Mail, DB;
use Carbon\Carbon; 
use App\Model\DiscovergigTransaction;
use App\Model\BorrowingChangeRequest;
use App\Model\Notification;
use App\Model\LoanRequest;
use Illuminate\Support\Facades\Validator;
class LoanController extends BaseController{
    public function __construct(){
		$this->middleware(array('auth','employee'));
    }  
    public function settings(Request $request){  
        return view('employee.loans.settings', $this->data);
    }
    public function loan_request(Request $request){  
        if($request->isMethod('post')){
            $validator = Validator::make($request->all(),  [
				'request_amount'    	=> 'required | numeric'
			]); 
			if($validator->fails())
				return response()->json(['status' => 0, 'msg' => "invalid request."], 200);
            if(Auth::user()->accounts->loan_amount > $request->request_amount )
                return response()->json(['status' => 0, 'msg' => "The request amount should be greater than lending power."], 200);  
            Notification::create([
                'notifications_fromuser' 	=>  Auth::user()->id,
                'notifications_touser'		=>  0,
                'notifications_value'		=> Auth::user()->accounts->name . ' is requested to increasing borrowing power to $' . number_format( $request->request_amount, 2),
                'notifications_type'		=> 'loan_change'
            ]);
            BorrowingChangeRequest::where('user_id', Auth::user()->id)
                                ->update([
                                    'status'        => 1
                                ]);
            BorrowingChangeRequest::create([
                'amount'        => $request->request_amount,
                'user_id'       => Auth::user()->id, 
                'status'        => 0
            ]);
            return response()->json(['status' => 1, 'msg' => "success", 'url' => route('loans.request') ], 200);
        }
        $borrowing_change_request   =   BorrowingChangeRequest::where('user_id',  Auth::user()->id)
                                                        ->where('status', 0)
                                                        ->orderBy('created_at', 'desc')
                                                        ->first(); 
        $this->borrowing_change_request =   $borrowing_change_request;
        return view('employee.loans.request', $this->data);
    } 
    public function history(Request $request){
        $loan_histories     =   LoanRequest::where('user_id', Auth::user()->id)
                                    ->orderBy('updated_at', 'desc')        
                                    ->paginate(15);
                                
        $this->loan_histories   = $loan_histories;
        return view('employee.loans.history', $this->data);
    }
    public function reject(Request $request, $id){
        $loan_request   =   LoanRequest::where('serial', $id)
                                    ->where('user_id', Auth::user()->id)
                                    ->where('status', 'pending')
                                    ->first();
        if(!isset($loan_request)){
            return redirect()->back()->with(['error' => "This request is already taken"]);
        }

        $loan_request->update([
            'status' => 'withdrawn'
        ]);

        return redirect()->back()->with(['message' => "This request is withdrawn successfully"]);
    }
}