<?php 
namespace App\Http\Controllers\Auth; 
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use App\Helpers\Mainhelper; 
use App\User;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
    
    protected $redirectTo = '/home';
 */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	/**
	 * The user has been authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	function authenticated(Request $request, $user)
	{
		if($user->email_verified_at == NULL){
			$user->email_verified_at = Carbon::now()->timestamp;
			$user->save();
		}
	} 
	public function autologout(Request $request){
        if(Auth::guard('web')->check()){
            auth::logout();
            session()->flash('message', 'Your session has time out. Please re-login to continue');
            return redirect()->route('login');
        } 
        return redirect()->route('login');
    }

	public function redirectTo(){
		$role = Auth::user()->role; 
		if($role == 3){
			return '/master/dashboard'; 
		}
		if($role == 2){
			return 'employer/profile';
		}
		if($role == 1){
			return 'profile';
		}
		return '/login'; 
	}

	public function login(Request $request){
		$validator  =  Validator::make($request->all(), [  
            'email'         => 'required | max:255',
            'password'      => 'required'
        ]);
        if($validator->fails()){ 
            return redirect()->back()->withInput()->withErrors($validator);
        }
		if( Mainhelper::valid_email( $request->email) ){
			$user = User::where('email', $request->email)
						->first();  
			if(!isset($user)){
				return redirect()->back()->withInput()->withErrors(['email' =>  'You entered an incorrect address or password.']);
			}
			if (Auth::attempt(['email' => $user->email, 'password' => $request->password]))
				return redirect()->route('home');
			else
				return redirect()->back()->withInput()->withErrors(['email' =>  'You entered an incorrect email address or password.']);
		}  
		if( Mainhelper::validate_phone_number( $request->email) ){
			$user = User::where('cphonenumber', Mainhelper::removephonenumberspecial($request->email))
						->first(); 
			if(!isset($user)){
				return redirect()->back()->withInput()->withErrors(['email' =>  'You entered an incorrect address or password.']);
			}

			if (Auth::attempt(['cphonenumber' => $user->cphonenumber, 'password' => $request->password]))
				return redirect()->route('home');
			else
				return redirect()->back()->withInput()->withErrors(['email' =>  'You entered an incorrect email address or password.']);
			
		} 
		return redirect()->back()->withInput()->withErrors(['email' =>  'You entered an incorrect email address or password.']); 
	}
}
