<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class SlackInfo extends Model{
    protected $table = 'Slack_Info';
    protected $primaryKey = 'Slack_Info_ID';
    protected $fillable =
    [
        'Slack_Info_Client_ID',
        'Slack_Info_Client_Secret',
        'Slack_Info_Token',
        'Company_ID', 
        'Slack_Info_Tempcode',
        'Client_Info_Deleted' 
    ];  
    public static function getChannels(){
        $token = "##";  

        $channels = array();
        $url = 'https://slack.com/api/conversations.list?token=' . $token;
        $getChannels = SlackInfo::runSlackCURL($url); 
        if($getChannels->ok){
            $channels = $getChannels->channels;  
        }  
        return $channels;    
    }  
    public static function runSlackCURL($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
        $result = curl_exec($ch);
        if (curl_errno($ch)){                       
            return "";
        }  
        curl_close ($ch);
        $result = json_decode($result);  
        return $result;             
    }
    public static function postMessageForError($type, $text){
        $token = "##";  
        switch($type){
            case 'errors':
                $channel = "token";
                break;
            case 'invalid':
                $channel = "token";
                break;      
            case 'notifications':
                $channel = "token";
                break;
        } 
        $url = 'https://slack.com/api/chat.postMessage'; 
        $fields = array(
            'token'   =>   $token,
            'channel' =>   $channel,
            'as_user' =>   false,
            'text'    =>   $text
        );
        $result = SlackInfo::runPostSlackCURL($url, $fields);
        return $result;
    }
    
    public static function runPostSlackCURL($url, $fields){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        $fields_string = "";
        foreach($fields as $key=>$value) {
            $fields_string .= $key.'='.$value.'&'; 
        }   
        rtrim($fields_string, '&'); 
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
        if (curl_errno($ch)){
            return "";
        }                
        curl_close ($ch);
        $result = json_decode($result);
        return $result; 
    }
    
}