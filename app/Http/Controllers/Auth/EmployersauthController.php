<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Model\Sendmail;
use App\Model\UsersAccount;
use App\Helpers\Mainhelper;
use App\Model\SlackInfo;
class EmployersauthController extends Controller{ 
    protected $redirectTo = '/'; 
	public function register(Request $request){   
		return view('auth/registeremployers');
    } 
	/**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(Request $request){
		$rules = array(
			'firstname'              => 'required',
			'lastname'               => 'required',
			'cphonenumber'           => 'required',
			'email'            		 => 'required|email|unique:users'
		); 
        $input_arr = array(
			'firstname'             => $request->input('firstname'),
			'lastname'              => $request->input('lastname'),
			'cphonenumber'          => $request->input('cphonenumber'),
			'email'                 => $request->input('email'), 
        );  
		$message= array(
			'firstname.required'	=>'Provide your first name.',
			'lastname.required'		=>'Provide your last name.',
			'cphonenumber.required '=>'Provide your Phone number.',
			'email.required'		=>'Please provide a valid email.',
			'email.email'			=>'Please provide a valid email.',
			'email.unique'			=>'Provided email already exists.' 
		);
        $validator 		= Validator::make($input_arr,$rules,$message);

        if( $validator->fails() ):  
            return Redirect::back()->withErrors($validator)->withInput();
		else: 
			$password 						= 	Mainhelper::generatepssword();
			$user 							= 	new User;
			$user->cphonenumber 			=  	Mainhelper::removephonenumberspecial($request->input('cphonenumber'));
			$user->password 				= 	bcrypt($password);
			$user->email 					= 	strtolower($request->input('email'));
			$user->role 					= 	'2';
			$user->phone_verified_status 	= 	0;
			$user->availability 			=	'active';
			$user->save();
			
			$user_account 				= new UsersAccount;
			$user_account->account_id 	= $user->id; 
			$user_account->name 		= ucfirst($request->input('firstname')) .' '.  strtoupper(substr(ucfirst($request->input('lastname')), 0, 1)) . '.'; 
			$user_account->firstname 	= ucfirst($request->input('firstname'));
			$user_account->lastname 	= ucfirst($request->input('lastname')); 
			$user_account->loan_fee     = 0;
			$user_account->loan_amount  = 0;
			$user_account->loan_enable  = 0;
			$user_account->save();
			
			/*
				$sms_string = "Hello, " . $user_account->firstname . ". This is your password to login " . $password;
				try{
					Mainhelper::sendSMSbyTwilio($user->cphonenumber, $sms_string);
				}
				catch(Exception $e){
				}
			*/

			$sendmail	= 	new Sendmail; 
			$maildata	= 	array(
								'template_name'	=>	'emails.password',
								'to_name'		=>	$user_account->firstname,
								'to_email'		=>	$user->email,
								'subject'		=>	'Account created',
								'password'		=>	$password
							);
			$sendmail->sendEmail($maildata); 
			//SlackInfo::postMessageForError("notifications", "The employeer " . $user_account->name . " has been registered");
			return redirect()->route('login')->withInput()->with('success','Registered successfully! Your account credentials have been sent to the email provided.');
		endif;  
    }
}