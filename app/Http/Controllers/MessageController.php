<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Skill;
use DB, Session;
use Auth;
use App\User;
use App\Model\UsersAccount;
use App\Model\MessageList;
use App\Model\Message;
use App\Model\Job;
use App\Model\Proposal;
use App\Helpers\Mainhelper;
class MessageController extends BaseController{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){ 
	} 
	public function messages(Request $request){ 
        $user = Auth::user();
        if($user->role == 1){
            $this->message_lists =    MessageList::where('to_user_id', Auth::user()->id)
                                            ->where('status', 0)
                                            ->orderBy("updated_at", 'desc')
                                            ->get();
        } 
        if($user->role == 2){
            $this->message_lists =    MessageList::where('user_id', Auth::user()->id)
                                            ->where('status', 0)
                                            ->orderBy("updated_at", 'desc')
                                            ->get();
        } 
        if($request->has('room')){
            $this->room_id = $request->room;
            Session::put('room_id', $request->room);
            Session::save();
        } 
        if(Session::has('room_id')){ 
            $this->room_id  =   Session::get('room_id');
        } 
        return view('messages.messages', $this->data);
	} 
    public function get_message_content(Request $request){
        $validator = Validator::make($request->all(),  [
            'message_list'    => 'required | max : 256',
            'request_type'    => 'required | max: 10', 
            'request_offset'  => 'required | max: 20'
        ]);
        if($validator->fails())
            return response()->json(['status' => 0, 'msg' => "please provide the message list."], 200);
        $validate_request = ['init', 'old', 'refresh', 'add'];
        if(!in_array($request->request_type, $validate_request)){
			return response()->json(['status' => 0, 'msg' => "Invalid request type."], 200);
		}

        $user   =    Auth::user();
        if($user->role == 1){
            $message_list  =    MessageList::where('to_user_id', $user->id)
                                        ->where('serial', $request->message_list)  
                                        ->first();
        }
        else{
            $message_list  =    MessageList::where('user_id', $user->id)
                                        ->where('serial', $request->message_list)  
                                        ->first();
        } 

        if(!isset($message_list)){
            return response()->json(['status' => 0, 'msg' => "Invalid messagelist."], 200); 
        }
        
        if($request->request_type == "init"){
            Session::put('room_id', $message_list->serial);
            Session::save();    
        }
         
        if($request->request_type == "old"){
            $offset         =   $request->request_offset;
            $old_message    =   Message::where(function ($query) use ($user) {
                                    $query->where('from_user',      $user->id) 
                                            ->orWhere('to_user',    $user->id);
                                })
                                ->where('serial', $offset)
                                ->first();
            if(!isset($old_message))
                return response()->json(['status' => 0, 'msg' => "Invalid message serial."], 200); 
        }

        if(($request->request_type == 'add') || ( $request->request_type == 'refresh')){
            $offset         =   $request->request_offset;
            $old_message    =   Message::where(function ($query) use ($user) {
                                    $query->where('from_user',      $user->id) 
                                            ->orWhere('to_user',    $user->id);
                                })
                                ->where('serial', $offset)
                                ->first();
            if(!isset($old_message))
                return response()->json(['status' => 0, 'msg' => "Invalid message serial."], 200); 

            $last_message   = $message_list->getLastMessage(); 
            
            if(($request->request_type == 'refresh') && ($last_message->id == $old_message->id))
                return  response()->json(['status' => 0, 'msg' => "no update."], 200); 
        }

        $to_user    =   $message_list->getToUser( $user->id ); 
        if($request->request_type == "add"){
            $validator = Validator::make($request->all(),  [
                'content'         => 'required | max: 512'
            ]);
            if($validator->fails())
                return response()->json(['status' => 0, 'msg' => "please provide the message content."], 200); 
            Message::addMessage($user->id, $to_user->id, $message_list->id, $request->content); 
        }

        $item_count     =   10;  
        $messages_obj   =   Message::where('message_list', $message_list->id)
                                    ->orderBy('id', 'desc'); 
        if($request->request_type == "old"){
           $messages_obj->where('id', '<', $old_message->id);
        } 

        if(($request->request_type == 'add') || ( $request->request_type == 'refresh')){
            $messages_obj->where('id', '>', $old_message->id);
        }

        if($request->request_type == "init"){
            $proposal   =  Proposal::where('job_id', $message_list->job_id)
                                ->first();
            if(isset($proposal) && ($proposal->status == 1)){
                $proposal->update([
                    'status'=> 2
                ]);
            }
        } 
        $messages       =   $messages_obj->take($item_count)
                                    ->get(); 
        $message_content = array();
        foreach($messages as $message){
            if($message->to_user == $user->id){
                if($message->message_read == 0){
                    $message->update([
                        'message_read' => 1
                    ]);
                }
            } 
            $date_value =  date('Y-m-d', strtotime($message->created_at)); 
            if (!array_key_exists($date_value,$message_content)){
                $message_content[$date_value] = array();
            }
            $message_content[$date_value][] = $message;
        }
        // $message_content
        $final_result = array();
        $array_index  = 0;
        foreach($message_content as $key => $message_content_array){
            $final_result[$array_index]  =   array();
            $final_result[$array_index]['key']   =   $key;
            $final_result[$array_index]['date']  =   view('messages.message_date', ['date' => $key])->render();
            $final_result[$array_index]['value'] =   view('messages.message_content', ['date' => $key, 'messages' => $message_content_array, 'me' => $user, 'to_user' => $to_user])->render();
            $array_index++;
        } 
        $new_messages       =  $message_list->caculateUnreadMessage( Auth::user()->id ); 
        //check enable old messasge
        $old_message_flag   =   0; 
        if(($request->request_type == 'init') || ( $request->request_type == 'old' )){
            $old_message    =   $messages[ count($messages) - 1 ];
            $last_message   =   Message::where('message_list', $message_list->id)
                                    ->orderBy('id') 
                                    ->first();
            if(isset($last_message)){ 
                if($last_message->id !== $old_message->id)
                    $old_message_flag = 1;
            }
        } 
        $return_value                       = array();
        if(($request->request_type == 'add') || ( $request->request_type == 'refresh')){
            $return_value['message_user_list'] =  view('messages.partial.message_list_user_item', ['message_list' => $message_list, 'show_content' => 1])->render();
        } 
        $total_new_messages                 =   Auth::user()->getUnreadMessages();       
        $return_value['total_new_messages'] =   $total_new_messages; 
        $return_value['status']             =   1;
        $return_value['old_message_flag']   =   $old_message_flag;
        $return_value['msg']                =   "success.";
        $return_value['new_messages']       =   $new_messages; 
        $return_value['result']             =   $final_result; 
        if($request->request_type == "init"){
            $return_value['header'] = view('messages.message_header', ['to_user' => $to_user])->render();
        }

        return response()->json($return_value, 200);
    } 
    public function sendmessageToEmployee(Request $request, $job_id){
        $job 	=	Job::where("serial", $job_id)
						->where('user_id', Auth::user()->id)
						->first();
		if(!isset($job))
			return response()->json(['status' => 0, 'msg' => "cannot find this job."], 200);
        $validator = Validator::make($request->all(),  [  
            'user_id'     => 'required',
            'message'     => 'required'
        ]);
        if($validator->fails())
            return response()->json(['status' => 0, 'msg' => "invalid request."], 200); 
        $user 	= 	User::where('serial', $request->user_id)
                        ->where('role', 1)
                        ->first();
        if(!isset($user))
            return response()->json(['status' => 0, 'msg' => "Cannot find this user."], 200); 
        $message_list =  MessageList::addMessageList(Auth::user()->id, $user->id, $job->id); 
        // add first message if exit
        $proposal = 	Proposal::where('job_id', $job->id)
								->where('user_id', $user->id)
								->first();
        if(isset($proposal) && ($proposal->status == 0)){
            $proposal->update([
                'status' => 1
            ]);  
            $message = Message::addMessage($user->id, Auth::user()->id,  $message_list->id, $proposal->coverletter, 1, $proposal->created_at);  
        }
        Message::addMessage(Auth::user()->id, $user->id, $message_list->id, $request->message); 

        return response()->json(array('status' => 1, 'url'=> route('messages') . '?room=' . $message_list->serial ,'msg' => 'success'));
    } 
    public function getuserlist(Request $request){ 
        $user = Auth::user();
        if($user->role == 1){
            $message_lists =    MessageList::where('to_user_id', $user->id)
                                            ->where('status', 0)
                                            ->orderBy("updated_at", 'desc')
                                            ->get();
        } 
        if($user->role == 2){
            $message_lists =    MessageList::where('user_id', $user->id)
                                            ->where('status', 0)
                                            ->orderBy("updated_at", 'desc')
                                            ->get();
        }

        if(!count($message_lists))
            return response()->json(array('status' => 0 , 'msg' => 'success')); 

        if(Session::has('room_id'))
            $room_id  =   Session::get('room_id');
        else
            $room_id  =  0;

        $total_new_messages =  Auth::user()->getUnreadMessages();
        $message_html       =   view('messages.partial.chat_user_list', ['message_lists' => $message_lists, 'refresh' => 'refresh' ,'room_id' => $room_id])->render();        
        return response()->json(array('status' => 1, 'message_html'=> $message_html, 'total_new_messages' => $total_new_messages  ,'msg' => 'success'));         
    } 
}