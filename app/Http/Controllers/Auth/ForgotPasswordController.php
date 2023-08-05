<?php
namespace App\Http\Controllers\Auth; 
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Forgetpassword;
use App\Model\Sendmail;
use App\Model\Token; 
use App\Model\UsersAccount; 
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Mainhelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class ForgotPasswordController extends Controller{
    /*
        |--------------------------------------------------------------------------
        | Password Reset Controller
        |--------------------------------------------------------------------------
        |
        | This controller is responsible for handling password reset emails and
        | includes a trait which assists in sending these notifications from
        | your application to your users. Feel free to explore this trait.
        |
    */
    use SendsPasswordResetEmails; 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    } 
	public function getform(){  
        return view('auth.email');
    } 
	public function generatepassword(Request $request){
	    $hashed_random_password =   str_random(15);
	   return response()->json(array('error'=>false,'hashed_random_password'=>$hashed_random_password));
    } 
	public function save(Forgetpassword $request){ 
        $data = User::where('cphonenumber',  Mainhelper::removephonenumberspecial($request->input('email')))->first();
        if(isset($data)){
            // send SMS
            $token = Token::create([
                'tokens_account' =>  Mainhelper::removephonenumberspecial( $request->input('email') )
            ]);
            $string = "Verification code is " . $token->tokens_token; 
            Mainhelper::sendSMSbyTwilio( Mainhelper::removephonenumberspecial( $request->input('email')),  $string ); 
            return redirect()->route('forget.form.phone', $token->tokens_serial);
        }
        
		$data           =   User::where('email', $request->input('email'))->first();
		$user           =   new User;
		$code           =   $user->generateRandomString();
		$activation_url =   route('reset.link.page', ['code'    =>  $code,'uid' =>  base64_encode($data->id)]);
		$users          =   User::find($data->id);
		$users->token   =   $code;
		$users->save(); 
		$sendmail       =   new Sendmail;
		$maildata       =   array(
                                'template_name'     =>  'emails.resetpassword',
                                'to_name'           =>  $users->accounts->firstname,
                                'to_email'          =>  $data->email,
                                'subject'           =>  'Forget Password',
                                'activation_url'    =>  $activation_url,
                                'email_regards_name'=>  trans('email.email_regards_name')
                            );
		$sendmail->sendEmail($maildata);
		return Redirect::back()->with(["success" => "Email has been sent with reset password instructions to the email address associated with your account"]); 
    }
    public function phone(Request $request, $code){
        $now    =   Carbon::now()->subMinutes(50);
        $token  =   Token::where('tokens_serial', $code)
                        ->where('created_at', '>=', $now->toDateTimeString())
                        ->first();
        if(!isset($token))
            return redirect()->route('forget.form')->withErrors(["oopsError" => "Sorry But this link is expired please try again"]); 
        if($request->isMethod('post')){
            $rules = array(
                'verification_code'     => 'required | max:256',                 
            );
            $input_arr = array(
                'verification_code'     => $request->input('verification_code')
            ); 
            $message= array(
                'verification_code.required'=>'Provide the verification code.'
            );
            $validator = Validator::make($input_arr,$rules,$message); 
            if( $validator->fails())
                return Redirect::back()->withErrors($validator)->withInput();
            
            if($token->tokens_token != $request->verification_code){
                $validator->errors()->add('verification_code', 'We are sorry but we cannot find this code. Please provide anothe code.');
                return Redirect::back()->withErrors($validator)->withInput();    
            } 
            $data           =   User::where('cphonenumber', $token->tokens_account)->first();
            $user           =   new User;
            $code           =   $user->generateRandomString(); 
            $users          =   User::find($data->id);
            $users->token   =    $code;
            $users->save();
            return redirect()->route('reset.link.page', ['code'=>$code,'uid'=>base64_encode($data->id)]); 
        }
        $data           =   array();
        $data['token']  =   $token->tokens_serial;
        return view('auth.phonereset', $data); 
    }
}