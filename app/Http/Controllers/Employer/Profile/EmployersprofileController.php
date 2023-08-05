<?php
namespace App\Http\Controllers\Employer\Profile;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Model\UsersAccount;
use App\Model\Media;
use App\Model\Verification;

class EmployersprofileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
		$this->middleware(array('auth','employer'));
    } 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $user 						= Auth::user();
		$data['user'] 				= User::find($user->id);
		$data['picverification'] 	= Verification::where('user_id', $user->id)->where('type', 'picture')->where('status', 'new')->get();
		$data['picrverification'] 	= Verification::where('user_id', $user->id)->where('type', 'picture')->where('status', 'rejected')->get();
		$data['picaverification'] 	= Verification::where('user_id', $user->id)->where('type', 'picture')->where('status', 'verified')->get(); 
		$data['setting_tab']		= "profile";
        return view('employer/profile/profile',$data);
    } 
	public function address(Request $request){
        $rules = array(
			'caddress'              => 'required',
			'city'               => 'required',
			'state'               => 'required',
			'zip'            		 => 'required',
			
        );
        $input_arr = array(
			'caddress'              => $request->input('caddress'),
			'city'                 => $request->input('city'),
			'state'                 => $request->input('state'),
			'zip'                 => $request->input('zip'),
			'lat'                 => $request->input('lat'),
			'lng'                 => $request->input('lng'),
			'oaddress'                 => $request->input('oaddress'),
           
        ); 
		
		$message= array(
			'caddress.required'=>'Please provide your Address.',
			'city.required'=>'Please provide your city.',
			'state.required'=>'Please provide your state.',
			'zip.required'=>'Please provide a valid zip.'
			
		);
        $validator = Validator::make($input_arr,$rules,$message);

        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
            //return Redirect::back()->withErrors($validator)->withInput();
        else: 

			$address 	=	$request->zip . ", " . $request->caddress . ", " . $request->city  . "," . $request->state . ",USA"; 
			$prepAddr 	= 	str_replace(' ','+',$address);  
			$geocode	=	file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyCJgcL_4zdeEI7q4E-crcMP19Jx8YCbWR8');

			$output		= 	json_decode($geocode);    
			if($output->status == 'OK'){
				$latitude 	= $output->results[0]->geometry->location->lat;
				$longitude 	= $output->results[0]->geometry->location->lng; 
			}
			else{
				$latitude 	= null;
				$longitude	= null;
			}

			$old_user 		= UsersAccount::find(Auth::user()->id)->toArray();
			$user 			= UsersAccount::find(Auth::user()->id);
			$user->caddress = $request->input('caddress');
			$user->city 	= $request->input('city');
			$user->state 	= $request->input('state');
			$user->zip 		= $request->input('zip');
			$user->lat 		= $latitude;
			$user->lng 		= $longitude;
			$user->oaddress = $request->input('oaddress');
			$user->save();

			$uh = new User(); 
			$uh->Updatehistory($old_user,$input_arr);
			
			return response()->json(['error'=>false,'success'=>'Updated successfully!']);
			//return redirect()->route('employee.profile')->withInput()->with('success','Updated successfully!');
		endif;  
    }
	
	public function misc(Request $request){
        $rules = array(
			'headline'               => 'required',
			'birthdate'              => 'required',
			'industry'               => 'required',
			'socialsecuritynumber'   => 'required',
			
        );
        $input_arr = array(
			'headline'                 => $request->input('headline'),           
			'birthdate'                => $request->input('birthdate'),           
			'industry'                 => $request->input('industry'),           
			'socialsecuritynumber'     => $request->input('socialsecuritynumber'),           
        ); 
		
		$message= array(
			'headline.required'=>'Please provide your headline.',
			'birthdate.required'=>'Please enter your birthdate.',
			'industry.required'=>'Please select your industry.',
			'socialsecuritynumber.required'=>'Please enter your social security number.',
			
		);
        $validator = Validator::make($input_arr,$rules,$message);

        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else: 
			$old_user = UsersAccount::find(Auth::user()->id)->toArray();
			$user = UsersAccount::find(Auth::user()->id);
			$user->headline = $request->input('headline');
			$user->birthdate = $request->input('birthdate');
			$user->industry = $request->input('industry');
			$user->socialsecuritynumber = encrypt($request->input('socialsecuritynumber'));
			$user->save();
			
			$uh = new User(); 
			$uh->Updatehistory($old_user,$input_arr);
			
			return response()->json(['error'=>false,'success'=>'Updated successfully!']);
		endif;  
    } 
	public function start(Request $request){
		if(Auth::user()->phone_verified_status == 0){
			$verified_status = Auth::user()->verified_status('phone'); 
			if($verified_status)
				return view('general.phoneverification');
			else
				return view('general.limitphoneverification');
		}
		else{
			return redirect()->route('employer.profile');
		}
	}
}
