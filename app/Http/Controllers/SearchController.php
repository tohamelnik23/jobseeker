<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request; 
use DB;
use Auth;
use Session;
use App\Model\Job;
use Illuminate\Support\Facades\Validator;
use App\Model\Proposal;
use App\Model\Role;
use App\Model\Invite;
use App\Model\ProposalAnswer;
use App\Model\MessageList;
use App\Model\Message;
use App\Model\Industry;
use App\User;
use App\Model\Offer;
use App\Model\MasterSetting;
use App\Model\Notification;
use App\Helpers\Mainhelper; 
use App\Http\Controllers\BaseController;

use Carbon\Carbon;

class SearchController extends BaseController{
    public function search(Request $request){ 
        $jobs_obj   =   Job::where('jobs.status', 1)
                            ->orderBy("jobs.updated_at", "desc") 
                            ->select("jobs.*")
                            ->join('subcategory', 'subcategory.id', 'jobs.job_type'); 
        $this->search_locationtype = 	"local";
        if($request->has('sortby_locationtype')){
            $this->search_locationtype = 	$request->sortby_locationtype;
        } 
        if($this->search_locationtype == "local"){ 
            $travel_distance    =   -1;
            if(!Auth::check() || (Auth::user()->role != 1)){
                if($request->zip_code){
                    $zip_code           =  $request->zip_code; 
                }
                else{
                    $PublicIP 			=   $request->ip();
                   // $PublicIP 			=   "198.255.66.27";
                    $zip_code 	        = 	Mainhelper::getZipByIP($PublicIP);    
                }
                if($request->sortby_distance){
                    $travel_distance  =  (int) $request->sortby_distance;
                }
                else{
                    $travel_distance  =  10;
                }
            }
            else{
                $zip_code = Auth::user()->accounts->zip;
                if(Auth::user()->accounts->travel_distance)
                    $travel_distance = Auth::user()->accounts->travel_distance;
            }
            if($travel_distance != -1){
                $latitude 	    = null;
                $longitude	    = null; 
                if($zip_code){
                    $address 	=	$zip_code .  ",USA"; 
                    $prepAddr 	= 	str_replace(' ', '+', $address);  
                    $geocode	=	file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyCJgcL_4zdeEI7q4E-crcMP19Jx8YCbWR8'); 
                    $output		= 	json_decode($geocode);    
                    if($output->status == 'OK'){
                        $latitude 	= $output->results[0]->geometry->location->lat;
                        $longitude 	= $output->results[0]->geometry->location->lng; 
                    }
                }
                if($latitude){ 
                    $jobs_obj   =   $jobs_obj->where('location_type', 'local')
                                        ->select( "jobs.*" , DB::raw('( 3959 * acos( cos( radians(' . $latitude . ') ) * cos( radians( jobs.lat ) ) * cos( radians( jobs.lng ) - radians(' . $longitude .') ) + sin( radians('.$latitude.') ) * sin( radians( jobs.lat ) ) ) ) AS distance'))
                                        ->having('distance', '<', $travel_distance);
                }
                else{ 
                    $jobs_obj       =   $jobs_obj->where('location_type', 'local1');
                }
            }
        }
        else{
            $travel_distance    =   -1;
            $jobs_obj           =   $jobs_obj->where('location_type',  $this->search_locationtype ); 
        } 
        if($request->sortby_jobtype){
            $jobs_obj       =   $jobs_obj->whereIn("payment_type", $request->sortby_jobtype); 
        }
        if($request->sortyby_category){
            $jobs_obj    =   $jobs_obj->whereIn("job_type", $request->sortyby_category); 
        } 
        if($request->sortby_jobtype)
			$this->search_jobtypes =  	$request->sortby_jobtype;
		else
			$this->search_jobtypes = 	[]; 
        
        if($request->sortby_type){
            $jobs_obj   =   $jobs_obj->whereIn("type", $request->sortby_type); 
        } 
        if($request->sortby_type)
            $this->search_types =  	$request->sortby_type;
        else
            $this->search_types = 	[]; 
        // categories
        if(!Auth::check() || (Auth::user()->role != 1)){
            $this->categories   =  Industry::where('deleted', 0)->get();  
        }
        else{
            $category_ids = array();
            $roles =  Auth::user()->getRoles();
            foreach($roles as $role){
                $subcategory  = $role->subcategory();
                if(isset($subcategory))
                    $category_ids[] = $subcategory->category_id;
            }
            $this->categories   =  Industry::where('deleted', 0)->whereIn('id', $category_ids)->get();  
        }
        if($request->has('category')){
            $current_category    =   Industry::where('serial', $request->category)->first();
            if(isset($current_category))
                $this->cur_category  =   $current_category->id;
            else
                $this->cur_category  =  -1;  
        }
        else{
            $this->cur_category =   $this->categories[0]->id;
            $current_category   =   $this->categories[0];
        }  
        $subcategory_ids    =   array();
        if(isset($current_category)){
            $subcategories      =   $current_category->subcategories;
            foreach($subcategories as $subcategory){
                $subcategory_ids[]  = $subcategory->id;
            }
        }
        else{
            $subcategory_ids[] = -1;
        } 
        $jobs                   =   $jobs_obj->whereIn('job_type', $subcategory_ids)->paginate(10);  
        $this->total_shifts     =   count($jobs_obj->whereIn('job_type', $subcategory_ids)->where('jobs.type', 'shift')->get());  
        
        $this->jobs             =   $jobs;
        $this->travel_distance  =   $travel_distance;
        $this->tab_type         =   'search';

        if(!Auth::check() || (Auth::user()->role != 1)){
            if(isset($zip_code))
                $this->zip_code     =  $zip_code;
        }
        return view('search.search', $this->data);
    }
    public function search_shifts(Request $request){
        $jobs_obj   =   Job::where('jobs.status', 1)
                            ->orderBy("jobs.updated_at", "desc")
                            ->where('type', "shift")
                            ->select("jobs.*")
                            ->join('subcategory', 'subcategory.id', 'jobs.job_type'); 
        $this->search_locationtype = 	"local";
        if($request->has('sortby_locationtype')){
            $this->search_locationtype = 	$request->sortby_locationtype;
        } 
        if($this->search_locationtype == "local"){
            $travel_distance    =   -1;
            if(!Auth::check() || (Auth::user()->role != 1)){
                if($request->zip_code){
                    $zip_code           =  $request->zip_code; 
                }
                else{
                    $PublicIP 			=   $request->ip();
                   // $PublicIP 			=   "198.255.66.27";
                    $zip_code 	        = 	Mainhelper::getZipByIP($PublicIP);    
                }
                if($request->sortby_distance){
                    $travel_distance  =  (int) $request->sortby_distance;
                }
                else{
                    $travel_distance  =  10;
                }
            }
            else{
                $zip_code = Auth::user()->accounts->zip;
                if(Auth::user()->accounts->travel_distance)
                    $travel_distance = Auth::user()->accounts->travel_distance;
            }
            if($travel_distance != -1){
                $latitude 	    = null;
                $longitude	    = null; 
                if($zip_code){
                    $address 	=	$zip_code .  ",USA"; 
                    $prepAddr 	= 	str_replace(' ', '+', $address);  
                    $geocode	=	file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyCJgcL_4zdeEI7q4E-crcMP19Jx8YCbWR8'); 
                    $output		= 	json_decode($geocode);    
                    if($output->status == 'OK'){
                        $latitude 	= $output->results[0]->geometry->location->lat;
                        $longitude 	= $output->results[0]->geometry->location->lng; 
                    }
                }
                if($latitude){ 
                    $jobs_obj   =   $jobs_obj->where('location_type', 'local')
                                        ->select( "jobs.*" , DB::raw('( 3959 * acos( cos( radians(' . $latitude . ') ) * cos( radians( jobs.lat ) ) * cos( radians( jobs.lng ) - radians(' . $longitude .') ) + sin( radians('.$latitude.') ) * sin( radians( jobs.lat ) ) ) ) AS distance'))
                                        ->having('distance', '<', $travel_distance);
                }
                else{ 
                    $jobs_obj       =   $jobs_obj->where('location_type', 'local1');
                }
            }
        }
        else{
            $travel_distance    =   -1;
            $jobs_obj           =   $jobs_obj->where('location_type',  $this->search_locationtype ); 
        }
        if($request->sortby_jobtype){
            $jobs_obj       =   $jobs_obj->whereIn("payment_type", $request->sortby_jobtype); 
        }
        if($request->sortyby_category){
            $jobs_obj    =   $jobs_obj->whereIn("job_type", $request->sortyby_category); 
        } 
        if($request->sortby_jobtype)
			$this->search_jobtypes =  	$request->sortby_jobtype;
		else
			$this->search_jobtypes = 	[]; 
        // categories
        if(!Auth::check() || (Auth::user()->role != 1)){
            $this->categories   =  Industry::where('deleted', 0)->get();  
        }
        else{
            $category_ids = array();
            $roles =  Auth::user()->getRoles();
            foreach($roles as $role){
                $subcategory  = $role->subcategory();
                if(isset($subcategory))
                    $category_ids[] = $subcategory->category_id;
            }
            $this->categories   =  Industry::where('deleted', 0)->whereIn('id', $category_ids)->get();  
        }
        if($request->has('category')){
            $current_category    =   Industry::where('serial', $request->category)->first();
            if(isset($current_category))
                $this->cur_category  =   $current_category->id;
            else
                $this->cur_category  =  -1;  
        }
        else{
            $this->cur_category =   $this->categories[0]->id;
            $current_category   =   $this->categories[0];
        }  
        $subcategory_ids    =   array();
        if(isset($current_category)){
            $subcategories      =   $current_category->subcategories;
            foreach($subcategories as $subcategory){
                $subcategory_ids[]  = $subcategory->id;
            }
        }
        else{
            $subcategory_ids[] = -1;
        }

        $jobs                   =   $jobs_obj->whereIn('job_type', $subcategory_ids)->paginate(10);  
        $this->total_shifts     =   count($jobs_obj->whereIn('job_type', $subcategory_ids)->where('jobs.type', 'shift')->get());  
        
        $this->jobs             =   $jobs;
        $this->travel_distance  =   $travel_distance;
        $this->tab_type         =   'search';

        if(!Auth::check() || (Auth::user()->role != 1)){
            if(isset($zip_code))
                $this->zip_code     =  $zip_code;
        } 
        return view('search.shift', $this->data);
    }

    public function search_employee(Request $request){
        $users_obj      =    User::where('role', 1)
                                ->where('start_stage', 10)
                                ->where('availability', 'active')
                                ->join('user_account', 'user_account.account_id',  'users.id') 
                                ->join('roles', 'roles.user_id', 'users.id'); 
        $travel_distance    =   -1; 
        $this->show_zipcode = 1;
        if(!Auth::check() || (Auth::user()->role != 2)){
            if($request->zip_code)
                $zip_code  =  $request->zip_code;
        }
        else{
            if(Auth::user()->accounts->zip){
                $this->show_zipcode = 0; 
                $zip_code = Auth::user()->accounts->zip;            
            }
        }

        if(!isset($zip_code)){
            $PublicIP 	    =   $request->ip();
            //$PublicIP 	=   "198.255.66.27";
            $zip_code 	    = 	Mainhelper::getZipByIP($PublicIP);    
        }

        $latitude 	    = null;
        $longitude	    = null; 
        if($zip_code){
            $address 	=	$zip_code .  ",USA"; 
            $prepAddr 	= 	str_replace(' ', '+', $address);  
            $geocode	=	file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyCJgcL_4zdeEI7q4E-crcMP19Jx8YCbWR8'); 
            $output		= 	json_decode($geocode);    
            if($output->status == 'OK'){
                $latitude 	= $output->results[0]->geometry->location->lat;
                $longitude 	= $output->results[0]->geometry->location->lng; 
            }
        }
        
        if($latitude){
            $users_obj   =   $users_obj->select('users.*'  ,"user_account.*", DB::raw('( 3959 * acos( cos( radians(' . $latitude . ') ) * cos( radians( user_account.lat ) ) * cos( radians( user_account.lng ) - radians(' . $longitude .') ) + sin( radians('.$latitude.') ) * sin( radians( user_account.lat ) ) ) ) AS distance'));
                //               ->having('travel_distance', '>=', 'distance');
        }
        else{
            $users_obj   =   $users_obj->select('users.*', "user_account.*")->where('user_account.travel_distance', -1);
        }
        // categories
        $this->categories       =   Industry::where('deleted', 0)->get();  
        if($request->has('category')){
            $current_category    =   Industry::where('serial', $request->category)->first();
            if(isset($current_category))
                $this->cur_category  =   $current_category->id;
            else
                $this->cur_category  =  -1;
        }
        else{
            $this->cur_category =   $this->categories[0]->id;
            $current_category   =   $this->categories[0];
        }

        $subcategory_ids    =   array();
        if(isset($current_category)){
            $subcategories          =   $current_category->subcategories;
            foreach($subcategories as $subcategory){
                $subcategory_ids[]  = $subcategory->id;
            }
        }
        else{
            $subcategory_ids[]  = -1;
        }
        $users                  =   $users_obj->whereIn('roles.subcategory', $subcategory_ids)->groupBy("users.id")->get();
        $result_users           =   array();
        foreach($users as $user){
            if($user->travel_distance){
                if($user->travel_distance > $user->distance ){
                    $result_users[] = $user;
                }
            }
            else{
                $result_users[] = $user;
            }
        }
        $this->users            =   $result_users;  
        $this->tab_type         =  'search';
        $this->travel_distance  =   $travel_distance;
        $this->zip_code         =   $zip_code;
        return view('search.search_employee', $this->data);
    } 
    public function jobs_details(Request $request, $id){ 
        $job 	=	Job::where("serial", $id)
                        ->where('status', '<>' , 0) 
                        ->first(); 
        if(!isset($job))
            abort(404); 
        $this->job      = $job;         
        $this->view_way = "public"; 
        return view('employer/jobs/jobdetails',$this->data);
    }
    public function proposals(Request $request, $id){
        $job 	=	Job::where("serial", $id)
                        ->where('status', 1) 
                        ->first(); 
        if(!isset($job)){
            if($request->isMethod('post'))
                return response()->json(['status'=> 0, 'msg'=> "This job is not exist or expired." ]);
            else
                abort(404);  
        }
        $proposal   =   Proposal::where('job_id', $job->id)
                        ->where('user_id', Auth::user()->id)
                        ->first(); 
        if(isset($proposal)){
            if($request->ajax())
                return response()->json(['status'=> 0, 'msg'=> "You have accepted this interview already." ]);
            else
                return redirect()->route('jobs_proposal_details', $proposal->serial)->with('error', "You have been proposed this already.");
        }
        if($request->isMethod('post')){
            $rules = array(
                'specialized_role'     => 'required',
                'coverletter'          => 'required', 
                'proposal_amount'      => 'required | numeric'
            );
            $message= array(
                'specialized_role.required'		=> 'Please provide the special role.',
                'coverletter.required'		    => 'Please provide the coverletter.',
                'proposal_amount.numeric'   	=> 'Invalid format ',
                'proposal_amount.required'		=> 'Please provide proposal amount.'
            );
            $validator  =   Validator::make($request->all(),$rules,$message); 
            if($validator->fails()):
                return response()->json(['status'=> 0, 'errors'=>$validator->errors()]);
            else:
                $role = Role::where('serial', $request->specialized_role)
                            ->where('user_id', Auth::user()->id)
                            ->where('is_deleted', 0)
                            ->first();
                if(!isset($role)){
                    return response()->json(['status'=> 0, 'msg'=> "This role is not exist." ]);
                }
                $flag               = 0;
                $questions          = $job->getQuestions(); 
                foreach($questions as $question){
                    if(($request->answer !== null) &&  isset($request->answer[$question->serial])){ 
                    }
                    else{
                        $flag = 1;
                    }
                } 
                if($flag){
                    return response()->json(['status'=> 0, 'msg'=> "Please provide the answer." ]);
                } 
                $proposal   =   Proposal::create([
                    'coverletter'       => $request->coverletter,
                    'proposal_amount'   => $request->proposal_amount,
                    'job_id'            => $job->id,
                    'user_id'           => Auth::user()->id,
                    'role'              => $role->id,
                ]);
                if($request->answer !== null){
                    foreach($request->answer as $answer_index => $request_answer){
                        ProposalAnswer::updateOrCreate([
                            'proposal' => $proposal->id,
                            'question' => $answer_index
                        ],[
                            'answer'   =>  $request_answer,
                            'deleted'   => 0
                        ]);
                    }
                } 
                $invite     =   Invite::where('user_id', Auth::user()->id)
                                    ->where('job_id', $job->id)
                                    ->where('status', 0)
                                    ->first();
                if(isset($invite)){
                    $invite->update([
                        'status' => 1
                    ]);
                    $proposal->update([
                        'status' => 2
                    ]);
                    //1. create message list, 2. add invite mesage 3. add proposal message
                    $message_list   =  MessageList::addMessageList($job->user_id, Auth::user()->id, $job->id);
                    Message::addMessage($job->user_id, Auth::user()->id,  $message_list->id, $invite->notes, 2, $invite->created_at);
                    Message::addMessage(Auth::user()->id, $job->user_id,  $message_list->id, $proposal->coverletter, 1, $proposal->created_at, $proposal->id);
                    $client = $job->getClient(); 
                    Notification::create([
                        'notifications_fromuser' 	=>  Auth::user()->id,
                        'notifications_touser'		=>  $client->id,
                        'notifications_value'		=>  Auth::user()->accounts->name . ' accepted your invitation to interview for the job "' . $job->headline . '".',
                        'notification_ref'          =>  $job->serial,
                        'notifications_type'		=> 'invited_accepted'
                    ]);
                    Session::flash('message',  'You have accepted the invitation successfully.'); 
                }
                else{
                    // jobs_proposals
                    Notification::create([
                        'notifications_fromuser' 	=>  Auth::user()->id,
                        'notifications_touser'		=>  $job->user_id,
                        'notifications_value'		=>  Auth::user()->accounts->name . ' applied to your job "' .  $job->headline . '".',
                        'notification_ref'          =>  $job->serial,
                        'notifications_type'		=> 'proposal_applied'
                    ]);
                    Session::flash('message',  'You have submitted the proposal successfully.');
                }
                $url = route('jobs_proposal_details', $proposal->serial);
                return response()->json(['status'=> 1, 'msg'=> "success.", 'url' => $url ]); 
            endif;
        } 
        $this->job              =   $job;   
        $this->user 	        = 	User::find(Auth::user()->id);
        $this->job_taker_fee    =   MasterSetting::getValue('job_taker_fee');  
        return view('employee/jobs/proposal',$this->data);
    }
    public function proposal_details(Request $request, $id){ 
        $proposal =     Proposal::where('serial', $id)
                            ->where('user_id', Auth::user()->id)
                            ->first(); 
        if(!isset($proposal))
            abort(404);
        $this->proposal         =  $proposal;
        $this->decline_messages =   DB::table("decline_reasons")->where('type', "proposal_freelancer")->orderBY("more_info")->get(); 
        return view('employee.jobs.detail', $this->data);
    }
    public function freelancer_detail(Request $request, $id){
		$freelancer =  	User::where('serial', $id)
                            ->where('role', 1)
							->first();  
		if(!isset($freelancer))
			abort(404);
		$freelancer->work_status    = "none"; 
		$this->freelancer           = $freelancer;
		return view('employer/jobs/userdetails',$this->data);
    }
    public function getfeedback(Request $request, $id){ 
        $validator = Validator::make($request->all(),  [ 
            'offset'        => 'required | integer',
        ]);  
        if($validator->fails())
            return response()->json(['status' => 0, 'msg' => "invalid offset."], 200);

        $job   =    Job::where('serial', $id)
                        ->first();
        if(!isset($job))
           return response()->json(array('status' => 0, 'msg' => 'Invalid job')); 
           
        $offset         =   (int) $request->offset; 
        $new_offset     =   $offset + 5;
        $client         =   $job->getClient(); 
        $this->offers   =   Offer::where('employer_id', $client->id)
                                ->where('status',   10)
                                ->orderBy('end_time', 'desc')
                                ->offset($offset)
                                ->take(10)
                                ->get();
        $this->client   =   $client;
        if(($offset == 0) || count($this->offers))
            $html  =   view('partial.feedback_detail', $this->data)->render();
        else
            $html   =  ""; 
        $total_feedbacks    =   Offer::where('employer_id', $client->id)
                                    ->where('status',   10) 
                                    ->count(); 
        if($total_feedbacks > $new_offset)
            $next_button = 1;
        else
            $next_button = 0;
        return response()->json(array('status' => 1, 'msg' => 'success', 'next_button' => $next_button, 'offset' => $new_offset  ,'html' => $html));

    }
}