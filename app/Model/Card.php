<?php 
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Card extends Model{
	protected $table 		= 'cards';
	protected $primaryKey 	= 'id';
	protected $fillable 	= ['user_id', 'deleted', 'expiration_mm', 'expirattion_yy', 'ext', 'card_type' ,'profile_id', 'status' ,'first_name', 'last_name', 'serial', 'profile_type', 'bill_address', 'primary_method'];
	public static function boot(){ 
	    parent::boot();
	    self::creating(function($model){
	    	$model->deleted 	=   0; 
            $model->serial 	    =   self::GenerateSerial();
            $latest_priority    =   self::where('deleted', 0) 
                                        ->first();
            if(isset($latest_priority)){
                $model->primary_method   =  0;
            }
            else{
                $model->primary_method   =  1;
            }
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
    public static function GenerateSerial($digit = 20){
        $chars  	=   array(0,1,2,3,4,5,6,7,8,9);
        $max 	    =   count($chars) - 1; 
        while(1){
            $sn = '';
            for($i = 0; $i < $digit; $i++)
                $sn     .=  $chars[rand(0, $max)];
            $apitoken   =   self::where('serial', $sn)->first();
            if(!isset($apitoken))
                break;
        }
        return $sn;
    } 
    public function getname(){
        $get_name 		= ucfirst($this->first_name) .' '.  strtoupper(substr(ucfirst($this->last_name), 0, 1)) . '.'; 
		return $get_name;
    } 
    public static function PayOrder($amount, $setting){
        $nmi    =   new NMI; 
        $nmi->setLogin(MasterSetting::getValue('live_transactionkey'));
        $nmi->setBilling( $setting);
        $nmi->setOrder( $setting); 
        $r = $nmi->doSale($amount , $setting['card_number'], $setting['mm'] . $setting['yy'],  $setting['cvc']);

        $response                   =   array(); 
        $nmi_resonse                =   $nmi->responses;
        if($nmi_resonse['response'] == 1)
            $response['status']         =  1;
        else{
            $response['status']         =  0;
            $response['msg']            =  $nmi_resonse['responsetext']; 
        }

        $response['responseCode']   =  $nmi_resonse['response_code'];
        $response['authCode']       =  $nmi_resonse['authcode'];
        $response['avsResultCode']  =  $nmi_resonse['avsresponse'];
        $response['cvvResultCode']  =  $nmi_resonse['cvvresponse'];

        if(isset($nmi_resonse['cc_number']) )
            $response['accountNumber']  =  $nmi_resonse['cc_number'];
        else
            $response['accountNumber']  =  "";

        if(isset($nmi_resonse['accountType']))
            $response['accountType']  =  $nmi_resonse['accountType'];
        else
            $response['accountType']  =  "";
        
        if(isset($nmi_resonse['transactionid']) )
            $response['transId']  =  $nmi_resonse['transactionid'];
        else
            $response['transId']  =  "";
        
        $response['inactive_card']  =  0; 
        $response['decline']        =  0;
        if(($nmi_resonse['response_code'] >= 200) && ($nmi_resonse['response_code'] < 300)){
            $response['decline']        =  1;
        } 
        return $response;
    } 
    public static function getResponsecodeResult($response_code){
        $process_result = array(); 
        switch ($response_code) {
            case '100':
                $process_result['status'] = 1;
                $process_result['msg']    = "Transaction was approved.";
                break;
            case '200':
                $process_result['status'] = 0;
                $process_result['msg']    = "Transaction was declined by processor.";
                break;
            case '201':
                $process_result['status'] = 0;
                $process_result['msg']    = "Do not honor.";
                break;
            case '202':
                $process_result['status'] = 0;
                $process_result['msg']    = "Insufficient funds.";
                break;
            case '203':
                $process_result['status'] = 0;
                $process_result['msg']    = "Over limit.";
                break;    
            case '204':
                $process_result['status'] = 0;
                $process_result['msg']    = "Transaction not allowed.";
                break;    
            case '220':
                $process_result['status'] = 0;
                $process_result['msg']    = "Incorrect payment information.";
                break;
            case '221':
                $process_result['status'] = 0;
                $process_result['msg']    = "No such card issuer.";
                break;
            case '222':
                $process_result['status'] = 0;
                $process_result['msg']    = "No card number on file with issuer.";
                break;
            case '223':
                $process_result['status'] = 0;
                $process_result['msg']    = "Expired card.";
                break;
            case '224':
                $process_result['status'] = 0;
                $process_result['msg']    = "Invalid expiration date.";
                break;
            case '225':
                $process_result['status'] = 0;
                $process_result['msg']    = "Invalid card security code.";
                break;
            case '226':
                $process_result['status'] = 0;
                $process_result['msg']    = "Invalid PIN.";
                break;
            case '240':
                $process_result['status'] = 0;
                $process_result['msg']    = "Call issuer for further information.";
                break;
            case '250':
                $process_result['status'] = 0;
                $process_result['msg']    = "Pick up card.";
                break;
            case '251':
                $process_result['status'] = 0;
                $process_result['msg']    = "Lost card.";
                break;
            case '252':
                $process_result['status'] = 0;
                $process_result['msg']    = "Stolen card.";
                break;
            case '253':
                $process_result['status'] = 0;
                $process_result['msg']    = "Fraudulent card.";
                break;
            case '260':
                $process_result['status'] = 0;
                $process_result['msg']    = "Declined with further instructions available. (See response text)";
                break;    
            case '261':
                $process_result['status'] = 0;
                $process_result['msg']    = " Declined-Stop all recurring payments.";
                break;
            case '262':
                $process_result['status'] = 0;
                $process_result['msg']    = "Declined-Stop this recurring program.";
                break;
            case '263':
                $process_result['status'] = 0;
                $process_result['msg']    = "Declined-Update cardholder data available.";
                break;
            case '264':
                $process_result['status'] = 0;
                $process_result['msg']    = "Declined-Retry in a few days.";
                break;
            case '300':
                $process_result['status'] = 0;
                $process_result['msg']    = "Transaction was rejected by gateway.";
                break;
            case '400':
                $process_result['status'] = 0;
                $process_result['msg']    = "Transaction error returned by processor.";
                break;
            case '410':
                $process_result['status'] = 0;
                $process_result['msg']    = "Invalid merchant configuration.";
                break;
            case '411':
                $process_result['status'] = 0;
                $process_result['msg']    = "Merchant account is inactive.";
                break;
            case '420':
                $process_result['status'] = 0;
                $process_result['msg']    = "Communication error.";
                break;
            case '421':
                $process_result['status'] = 0;
                $process_result['msg']    = "Communication error with issuer.";
                break;
            case '430':
                $process_result['status'] = 0;
                $process_result['msg']    = "Duplicate transaction at processor.";
                break;
            case '440':
                $process_result['status'] = 0;
                $process_result['msg']    = "Processor format error.";
                break;
            case '441':
                $process_result['status'] = 0;
                $process_result['msg']    = "Invalid transaction information.";
                break;
            case '460':
                $process_result['status'] = 0;
                $process_result['msg']    = "Processor feature not available.";
                break;
            case '461':
                $process_result['status'] = 0;
                $process_result['msg']    = "Unsupported card type.";
                break; 
            default:
                $process_result['status'] = 0;
                $process_result['msg']    = "Unkown Error.";
                break;
        }
        return $process_result;         
    } 
    public static function  createProfileGlobal($settings){ 
        $response               =   array();
        $response['status']     =   0; 
        $nmi = new NMI;
        $nmi->setLogin(MasterSetting::getValue('live_transactionkey')); 
        $billing_info               = array(); 
        $nmi->setBilling( $settings );  
        $nmi->AddOrUpdateCustomerRecord($settings['card_number'], $settings['mm'] . $settings['yy']); 
        $nmi_resonse                =   $nmi->responses;
        $response   = array();
        if($nmi_resonse['response'] == "1"){
            $response['status']                     =  1; 
            if(isset($nmi_resonse['customer_vault_id']))
                $response['customerProfile']            =  $nmi_resonse['customer_vault_id'];
            else
                $response['customerProfile']            =  null;

            $response['customerPaymentProfile']     =  null;
        }
        else{
            $response['status']     =  0;
            $response['msg']        =  $nmi_resonse['responsetext']; 
        }
        return $response; 
    }
    public function  payOrderWithProfile($amount, $settings){
        $nmi    =   new NMI; 
        $nmi->setLogin(MasterSetting::getValue('live_transactionkey')); 
        $nmi->PaywithProfile( $amount,  $this->profile_id ,  $settings['refId']);  
        $response                   =   array(); 
        $nmi_resonse                =   $nmi->responses;
        
        if($nmi_resonse['response'] == 1)
            $response['status']         =  1;
        else{
            $response['status']         =  0;
            $response['msg']            =  $nmi_resonse['responsetext'];

            if(strpos($response['msg'], 'Invalid Customer Vault ID specified') !== false)
                $response['inactive_card']  =   1;
            else
                $response['inactive_card']  =   0;
        }
        $response['decline']            =  0; 
        if(($nmi_resonse['response_code'] >= 200) && ($nmi_resonse['response_code'] < 300)){
            $response['decline']        =  1;
        }
        $response['responseCode']   =  $nmi_resonse['response_code'];
        $response['authCode']       =  $nmi_resonse['authcode'];
        $response['avsResultCode']  =  $nmi_resonse['avsresponse'];
        $response['cvvResultCode']  =  $nmi_resonse['cvvresponse'];
        $response['cavvResultCode'] =  "";  
        if(isset($nmi_resonse['cc_number']) )
            $response['accountNumber']  =  $nmi_resonse['cc_number'];
        else
            $response['accountNumber']  =  "";

        if(isset($nmi_resonse['cc_type']) )
            $response['accountType']  =  $nmi_resonse['cc_type'];
        else
            $response['accountType']  =  "";
        
        if(isset($nmi_resonse['transactionid']) )
            $response['transId']  =  $nmi_resonse['transactionid'];
        else
            $response['transId']  =  "";  
        return $response; 
    }
    public function setPrimary(){
        Card::where('user_id', $this->user_id)
            ->where('deleted', 0)
            ->update([
                'primary_method' =>  0
            ]);
        $model->primary_method  = 1;
        $model->save();
    }
}