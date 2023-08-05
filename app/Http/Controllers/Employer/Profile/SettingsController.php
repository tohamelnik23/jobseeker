<?php
namespace App\Http\Controllers\Employer\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use App\Model\Transaction;
use App\Model\Card;
use App\Helpers\Mainhelper;
use App\Model\Address;
use Auth;
class SettingsController extends BaseController{  
    public function __construct(){
		$this->middleware(array('auth','employer'));
	}
    public function addCard(Request $request){
        $validator = Validator::make($request->all(),  [
			'card_number'       => 'required | max : 16 | min: 10',
			'first_name'        => 'required | max : 256',
			'last_name'		    => 'required | max : 256',
			'mm'		        => 'required | numeric',
			'yy'		  	    => 'required | numeric',
			'security_code'		=> 'required | numeric'		 
        ]);
        if($validator->fails())
			return response()->json(['status' => 0, 'msg' => "Please provide more details."], 200);
        // check card type  
        /*
        use_another_billing_address
        if( Mainhelper::validateChecksum($request->card_number) !== true){
        }
        */ 
        $sendData                       =   array(); 
        $sendData['refId']              =   Transaction::GenerateSerial();  
        $sendData['tax_amount']         =   0;
        $sendData['shipping']           =   0;
        $sendData['description']        =   "Discover Gig Card Verification";   
        $sendData['card_number']        =   $request->card_number;
        
        $mm = (int) $request->mm;
        if($mm  < 10)
            $sendData['mm']                 =   '0'. $mm;
        else
            $sendData['mm']                 =   $request->mm;
        
        $sendData['yy']                 =   $request->yy;
        $sendData['cvc']                =   $request->security_code; 
        $sendData['ip_address']         =   $request->ip(); 
        $sendData['first_name']         =   $request->first_name;
        $sendData['last_name']          =   $request->last_name; 
        
        $user           =   Auth::user(); 
        if(isset($request->use_another_billing_address)){
            $sendData['address1']            =   $request->address1;
            $sendData['address2']            =   $request->address2;
            $sendData['city']                =   $request->city;
            $sendData['state']               =   $request->state;
            $sendData['zip']                 =   $request->zip;
            $sendData['country']             =   "USA"; 

            $order_bill_address     =   Address::updateOrCreate([
                    'address_name1'         => $request->address1,
                    'address_name2'         => $request->address2,
                    'address_city'          => $request->city,
                    'address_state'         => $request->state,
                    'address_zipcode'       => $request->zip,
                    'user_id'               => Auth::user()->id
            ],[ 
                
            ]); 

        }
        else{
            $user_account                    =   $user->accounts;
            $sendData['address1']            =   $user_account->caddress;
            $sendData['address2']            =   $user_account->oaddress;
            $sendData['city']                =   $user_account->city;
            $sendData['state']               =   $user_account->state;
            $sendData['zip']                 =   $user_account->zip;
            $sendData['country']             =   "USA"; 
            
            $order_bill_address     =   Address::updateOrCreate([
                    'address_name1'         => $user_account->caddress,
                    'address_name2'         => $user_account->oaddress,
                    'address_city'          => $user_account->city,
                    'address_state'         => $user_account->state,
                    'address_zipcode'       => $user_account->zip,
                    'user_id'               => Auth::user()->id
            ],[  
            ]); 
        } 
        $sendData['email']                  =   $user->email;
        $sendData['phone']                  =   $user->cphonenumber;  
        $pay_result                         =   Card::payOrder(10, $sendData);
        
        if($pay_result['status']){
            $transaction = Transaction::create([
                'amount'        =>  10,
                'status'        => 'success',
                'type'          => 'charge',
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
        }
        else{
            $error_msg = "There was a system problem.  Please retry in a few minutes.";  
            return response()->json(array('status' => 0, 'msg' => $error_msg), 200);
        }
        // create card profile 
        $sendData['description']    =   date('Y-m-d h:i:s'); 
        $sendData['card_number']    =   $request->card_number;  
        $sendData['mm']             =   $request->mm;
        $sendData['yy']             =   $request->yy;
        $sendData['cvc']            =   $request->security_code; 
        $profile                    =   Card::createProfileGlobal($sendData); 
        $card                       = 	Card::updateOrCreate([
                                                'user_id'        => Auth::user()->id,
                                                'expiration_mm'  => $request->mm,
                                                'expirattion_yy' => $request->yy,
                                                'first_name'     => $request->first_name,
                                                'last_name'      => $request->last_name, 
                                                'card_type'      => Mainhelper::getCCType( $request->card_number ),
                                                'ext'            => Mainhelper::makeCardExtension($request->card_number),  
                                                'bill_address'   => $order_bill_address->id,
                                                'status'         => 'unverified'
                                            ]);
        $transaction->update([
            'card_id'  => $card->id
        ]);
        if($profile['status']){ 
            $card->update([
                'profile_id'     => $profile['customerProfile'],
                'payment_id'     => $profile['customerPaymentProfile'],
                'status'         => 'verified'
            ]);
        } 
        // do void
        return response()->json(array('status' => 1, 'msg' =>  'success'), 200);  
    }
    public function deposit_methods(Request $request){
        // deposit methods
        $cards              =   Card::where('user_id', Auth::user()->id)
                                    ->where('deleted', 0)
                                    ->orderBy('primary_method', 'desc')
                                    ->get();
        $this->cards        =   $cards;
        $this->setting_tab  =   "deposit_methods";
        return view('general.deposit_methods', $this->data);
    }
    public function set_primary_card(Request $request, $id){
        $card   =   Card::where('user_id', Auth::user()->id)
                        ->where('serial', $id)
                        ->first();
        if(!isset($card))
            abort(404);
        if($card->primary_method == 0){
            Card::where('user_id', Auth::user()->id)
                ->update([
                    'primary_method' => 0
                ]);
            $card->update([
                'primary_method' => 1
            ]);
        }
        return redirect()->back()->with('message', "The card is set primary successfully.");
    }
    public function remove_card(Request $request, $id){ 
        $card   =   Card::where('user_id', Auth::user()->id)
                        ->where('serial', $id)
                        ->where('deleted', 0)
                        ->first();
        if(!isset($card))
            abort(404); 
        $card->update([
            'deleted' => 1
        ]); 
        if($card->primary_method == 1){
            $card   =   Card::where('user_id', Auth::user()->id) 
                            ->where('deleted', 0)
                            ->first();
            if(isset($card)){
                $card->update([
                    'primary_method' => 1
                ]);
            }
        }
        return redirect()->back()->with('message', "The card is deleted successfully.");
    }
}