<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Resetpassword;
use App\Model\Sendmail;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

	public function getform($activationCode,$uid)
    {
       $userData = User::where(array('id'=>base64_decode($uid),'token'=>$activationCode))->first();
		if(!empty($userData)):
			$view=array();
			$view['code']=$activationCode;
			$view['uid']=$uid;
			return view('auth.reset',$view);		
		else:
			return redirect()->route('forget.form')->withErrors(["oopsError" => "Sorry But this link is expired please try again"]);
		endif;
    }
    
	public function save(Resetpassword $request){   
		$code = $request->input('code');
		$uid = $request->input('uid');
		
		$userData                   =  User::where(array('id'=>base64_decode($uid),'token'=>$code))->first();
		$userupdate                 =  User::find($userData->id);
		$userupdate->password       =  Hash::make($request->input('newpassword'));
		$userupdate->updated_at     =  Carbon::now()->toDateTimeString();
		$userupdate->token='';
		$userupdate->save();
		return redirect()->route('login')->with(["success" => "Your password has been reset successfully."])->withInput();  
    }
}
