<?php 
// any invite, any job edit, any offer, any message 
namespace App\Console\Commands; 
use Illuminate\Console\Command;
use App\Model\MasterSetting;
use App\Model\Notification;
use App\Model\Offer;
use Carbon\Carbon;
class OfferExpired extends Command{
    protected $description 	= 'This is for offer expired.';
    protected $signature 	= 'offer:expired';
    public function __construct(){
        parent::__construct();
    }
    public function handle(){ 
        $expire_offer_date      =   MasterSetting::getValue('expire_offer_date');
        $now                    =   Carbon::now()->subDays($expire_offer_date);
        $offers                 =   Offer::where('status', 0)
                                        ->where('start_time', '<=', $now->toDateTimeString())
                                        ->get();
        foreach($offers as $offer){  
            Notification::create([
                'notifications_fromuser' 	=>  0,
                'notifications_touser'		=>  $offer->employer_id,
                'notifications_value'		=>  "The offer '" . $offer->contract_title . "' is expired.",
                'notification_ref'          =>  $offer->serial,
                'notifications_type'		=> 'offer_expired'
            ]); 
            $offer->update([
                'status' => 4
            ]); 
        }
    }
}