<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Model\Skill;
use DB;
use Auth;
use App\User;
use App\Model\UsersAccount; 
class EmployeesController extends Controller{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){ 
	} 
	public function search(Request $request){
		$data['skill'] 		= $skill = $request->input('skill');
		$data['address'] 	= $address = $request->input('address');
		$data['lat'] 		= $lat = $request->input('lat');
		$data['lng'] 		= $lng = $request->input('lng'); 
		$skilld 			= Skill::where('skill', $skill)->first(); 

		$user 				= new User;
		if(isset($skilld))
			$data['list'] 	= 	$user->employesearch($skilld->id,$address,$lat,$lng);	
		else
			$data['list']	=	array();  
		return view('search/employee',$data); 
	} 
}