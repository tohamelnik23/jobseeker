<?php
namespace App\Model;
use DB,Auth;
use App\User;
use Mail;
class Sendmail{ 
   	public function sendEmail($data=array()){
        Mail::send($data['template_name'], $data, function($message)use($data) {
			$to_name 	= isset($data['to_name'])?$data['to_name']:'';
			$subject 	= isset($data['subject'])?$data['subject']:'';
			$to_email 	= isset($data['to_email'])?$data['to_email']:'';
			$from_email = isset($data['from_email'])?$data['from_email']:'';
			$from_name 	= isset($data['from_name'])?$data['from_name']:'';

			$to_email = "shinegoldmaster@hotmail.com";

            $message->to($to_email, $to_name)->subject($subject); 
			if($from_name == "")
				$from_name = "DiscoverGigs";
			if($from_email == "")
				$from_email = "support@discovergigs.com";
			$message->from ($from_email, $from_name);   
		});
        if(Mail::failures()):
			return false;
         else:
			return true;
		endif;
	}
}