<?php 
namespace App\Helpers; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use GuzzleHttp\Client as HttpClient; 
use App\User; 
use Illuminate\Support\Facades\Storage;
use File, Session, DB, Mail, Config, URL, Auth, Cart;  
use Carbon\Carbon; 
class Mainhelper extends Authenticatable{ 
	public static function getJobCloseReasons(){
		$reasons = [
            ['id' 	=> 1, 'reason' => 'Accidental job posting creation'],
            ['id'	=> 2, 'reason' => 'All positions filled'],
            ['id'	=> 3, 'reason' => 'Filled by alternate source'],
            ['id'	=> 4, 'reason' => 'No freelancer for requested skills'],
            ['id'	=> 5, 'reason' => 'Project was cancelled'],
        ];
		$reasons = json_decode(json_encode($reasons));
		return $reasons;
	}
	public static function getZipByIP($ip){
		if($ip == "127.0.0.1")
			$ip = "198.255.66.27";
		$api_key  = "42f1c8fccb4ee8bf5f0931473cc7ca01"; 
		$url 	  = 'https://api.snoopi.io/' . $ip. '?apikey='. $api_key; 
		$json  	  = file_get_contents($url);
		$json  	  =  json_decode($json ,true);
		if(isset($json['Postal']))
			return $json['Postal'];
		else
			return "";
	}
	public static function sendSMSbyTwilio($phonenumber, $content){ 
		$sid 	= "AC4004aa39fa0e6ceb76c1c6ed45cf8e69";
		$token 	= "896375ed9b4cc959e0d4b591988c017a";
		$client = new \Twilio\Rest\Client($sid, $token); 
		try{
			$message = $client->messages->create(
				$phonenumber,
				[
					'from' => '13022029804', // From a valid Twilio number
					'body' =>  $content
				]
			); 
			return $message->sid;	  
		} catch (\Twilio\Exceptions\RestException $e) {
			return 0;	
		}
	} 
    public static function generatepssword(){
		$str = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','e','d','f','g','h','j','k','l','m','n','p','q','r','s','t','u','v','x','x','y','z','2','3','4','5','6','7','8','9','!','@','#','$','%','&','*','(',')','_','-','=','+');
		$result = "";
		for($index = 0; $index < 6; $index++){
			$result .= $str[mt_rand(0, 69)];   
		} 
		$str = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$result .=  $str[mt_rand(0, 25)];
		 
		$str = array('!','@','#','$','%','&','*','(',')','_','-','=','+');
		$result .=  $str[mt_rand(0, 12)]; 
		return $result;     
	} 
	public static function valid_email($email) {
        return !!filter_var($email, FILTER_VALIDATE_EMAIL);   
	} 
    public static function validate_phone_number($phone){ 
        $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT); 
        $phone_to_check = str_replace("-", "", $filtered_phone_number);
        $phone_to_check = str_replace("+", "", $filtered_phone_number);
        $phone_to_check = str_replace(" ", "", $phone_to_check);
        $phone_to_check = str_replace("(", "", $phone_to_check);
        $phone_to_check = str_replace(")", "", $phone_to_check); 
        if(strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14){
			return false; 
        }else{
			return true;
        }
	} 
	public static function removephonenumberspecial($str, $add_usa = 1){
		$str = str_replace("-", "", $str);
		$str = str_replace("+", "", $str);
		$str = str_replace("(", "", $str);
		$str = str_replace(")", "", $str);
		$str = str_replace(".", "", $str);
		$str = str_replace(" ", "", $str);
		if($add_usa){
			if(substr($str, 0, 1) != '1'){
				$str = '1' . $str;                                       
			}
		}
		return $str;
	} 
	public static function getMonthArray(){
		$result  =  ['January', 'Febrary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		return $result;
	}  
	public static function getMonth($index){
		$result  =  ['January', 'Febrary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		return $result[$index - 1];
	}   
	public static function getStateFromValue($key){
		$states = array(
			'AL'=>'Alabama',
			'AK'=>'Alaska',
			'AZ'=>'Arizona',
			'AR'=>'Arkansas',
			'CA'=>'California',
			'CO'=>'Colorado',
			'CT'=>'Connecticut',
			'DE'=>'Delaware',
			'DC'=>'District of Columbia',
			'FL'=>'Florida',
			'GA'=>'Georgia',
			'HI'=>'Hawaii',
			'ID'=>'Idaho',
			'IL'=>'Illinois',
			'IN'=>'Indiana',
			'IA'=>'Iowa',
			'KS'=>'Kansas',
			'KY'=>'Kentucky',
			'LA'=>'Louisiana',
			'ME'=>'Maine',
			'MD'=>'Maryland',
			'MA'=>'Massachusetts',
			'MI'=>'Michigan',
			'MN'=>'Minnesota',
			'MS'=>'Mississippi',
			'MO'=>'Missouri',
			'MT'=>'Montana',
			'NE'=>'Nebraska',
			'NV'=>'Nevada',
			'NH'=>'New Hampshire',
			'NJ'=>'New Jersey',
			'NM'=>'New Mexico',
			'NY'=>'New York',
			'NC'=>'North Carolina',
			'ND'=>'North Dakota',
			'OH'=>'Ohio',
			'OK'=>'Oklahoma',
			'OR'=>'Oregon',
			'PA'=>'Pennsylvania',
			'RI'=>'Rhode Island',
			'SC'=>'South Carolina',
			'SD'=>'South Dakota',
			'TN'=>'Tennessee',
			'TX'=>'Texas',
			'UT'=>'Utah',
			'VT'=>'Vermont',
			'VA'=>'Virginia',
			'WA'=>'Washington',
			'WV'=>'West Virginia',
			'WI'=>'Wisconsin',
			'WY'=>'Wyoming',
		); 
		return $states[$key];
	} 
	public static function makeReadableDateFormat($date){ 
		$current_date = Carbon::createFromFormat('Y-m-d', $date);
		$now 		  = Carbon::now(); 
		if($now->toDateString() == $current_date->toDateString()) return "Today";
		if( $now->diffIndays($current_date) == 1) return "Yesterday"; 
		$first_day = Carbon::now()->startOfWeek(); 
		if( $first_day->diffIndays($current_date, false) >= 0) return $current_date->format('l'); 
		return $current_date->format('l, F m,Y');
	}
	public static function makeCardExtension($string){
		$last_string =  substr($string, -4);
		$string 	 =  $last_string;
		return $string;
	} 
	public static function getCCType($cardNumber) {
		$cardNumber = preg_replace('/\D/', '', $cardNumber);  
		$len = strlen($cardNumber);
		if ($len < 15 || $len > 16) {
			throw new Exception("Invalid credit card number. Length does not match");
		}else{
			switch($cardNumber) {
				case(preg_match ('/^4/', $cardNumber) >= 1):
					return 'Visa';
				case(preg_match ('/^5[1-5]/', $cardNumber) >= 1):
					return 'Mastercard';
				case(preg_match ('/^3[47]/', $cardNumber) >= 1):
					return 'Amex';
				case(preg_match ('/^3(?:0[0-5]|[68])/', $cardNumber) >= 1):
					return 'Diners Club';
				case(preg_match ('/^6(?:011|5)/', $cardNumber) >= 1):
					return 'Discover';
				case(preg_match ('/^(?:2131|1800|35\d{3})/', $cardNumber) >= 1):
					return 'JCB';
				default:
					throw new Exception("Could not determine the credit card type.");
					break;
			}
		}
	} 
	public static function validateChecksum($number) { 
		// Remove non-digits from the number
		$number=preg_replace('/\D/', '', $number); 
		// Get the string length and parity
		$number_length = strlen($number);
		$parity = $number_length % 2; 
		// Split up the number into single digits and get the total
		$total=0;
		for ($i=0; $i<$number_length; $i++) {
			$digit=$number[$i]; 
			// Multiply alternate digits by two
			if ($i % 2 == $parity) {
				$digit*=2; 
				// If the sum is two digits, add them together
				if ($digit > 9) {
					$digit-=9;
				}
			} 
			// Sum up the digits
			$total+=$digit;
		} 
		// If the total mod 10 equals 0, the number is valid
		return ($total % 10 == 0) ? TRUE : FALSE; 
	}
	public static function getWeekName($week_index){
		if($week_index > 6)
			$week_index = 0;
		$week_days = ['SUN', 'MON','TUE','WED','THU','FRI','SAT'];
		return $week_days[$week_index];
	} 
	public static function getAroundAmount($value){ 
		if($value == 0) return 0; 
		$log_value 		=  floor(log10 ($value) );
		$power_value  	=  pow(10, $log_value);
		$result 		=  intval(floor($value / $power_value));
		$string 		= ""; 
		if($log_value <= 2){ 
			$power_value  	=  pow(10, $log_value);
			$string 		=  $result * $power_value . "+";
		}
		elseif($log_value <= 5){
			$log_value  	=  $log_value - 3;
			$power_value  	=  pow(10, $log_value);
			$string 		=  $result * $power_value . "k+";
		}
		else{
			$log_value =  $log_value - 6;
			$power_value  	=  pow(10, $log_value);
			$string 		=  $result * $power_value . "M+";
		}
		return $string;
	}
	public static function formatNumber($number ){
		$number = str_replace("+", "", $number);
		$number = str_replace("-", "", $number);
		$number = str_replace(" ", "", $number);
        $number = str_replace("(", "", $number);
        $number = str_replace(")", "", $number); 
        
		if(preg_match( '/^\d(\d{3})(\d{3})(\d{4})$/', $number,  $matches)){
		    $result = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
		    return $result;
		}
		if(preg_match( '/^(\d{3})(\d{3})(\d{4})$/', $number,  $matches)){
		    $result = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
		    return $result;
		}
		return $number;
	}
	// Generate File
	public static function generateRandomFilename($dir, $filename){
		$digits 	= 	6;
		if(strrpos($filename, '.') != false){
			$extension 		=   "." . substr(strrchr($filename, "."), 1);
			$filename_body 	= 	substr($filename, 0, strrpos($filename, '.'));
		}
		else{ 
			$filename_body 	= 	$filename;   
			$extension 		= 	"";
		}   
		while(1){                    
			$result = '';            
			for($i = 0; $i < $digits; $i++){      
				$result .= mt_rand(0, 9);           
			} 
			$exists 	= 	Storage::disk('spaces')->has(env('DO_SPACES_DIR') . '/' . $dir . '/' . $filename_body .   $result .   $extension); 
			if (!$exists){
				break;
			}  
		} 
		$filename_body  .=  $result . $extension;
		return  $filename_body;
	}
}