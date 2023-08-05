<?php 
namespace App\Http\Controllers\master; 
use App\Http\Controllers\BaseController;
use App\User;
use Auth;
use Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Notification;

class NotificationController extends  BaseController{
    public function index() {
        $this->notifications    =   Notification::where('notifications_touser', 0)
                                        ->orderBy('notifications_readby')
                                        ->orderBy('created_at', 'desc')
                                        ->select("notifications.*", 'user_account.name')
                                        ->leftJoin('user_account', 'user_account.account_id', 'notifications.notifications_fromuser')
                                        ->paginate(15);
		return view('master.notification.index', $this->data);
    } 
    public function markasread(Request $request, $id){
        $notification   =      Notification::where('notifications_touser', 0)  
                                    ->where('notifications_serial', $id)
                                    ->first();
        if(!isset($notification))
            abort(404); 
        $notification->update([
            'notifications_readby' => 1
        ]);
        return redirect()->back()->with('message', 'The notification is set as read successfully.');
    }  
    public function action(Request $request, $id){
        dd($id);
    } 
}