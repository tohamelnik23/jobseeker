<?php
namespace App\Console\Commands; 
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Model\MasterSetting;
use App\Model\Offer;
use App\Model\JobSentWarning;
class OfferExpiring extends Command{
    protected $description 	= 'This is for offer expiring.';
    protected $signature 	= 'offer:expiring';
    public function __construct(){
        parent::__construct();
    }
    public function handle(){ 
        $expire_offer_date      =   MasterSetting::getValue('expire_offer_date');
        $expiring_offer_date    =   $expire_offer_date - 1;
        $now                    =   Carbon::now()->subDays($expiring_offer_date);
        $offers                 =   Offer::where('status', 0)
                                        ->where('start_time', '<=', $now->toDateTimeString())
                                        ->get();
        foreach($offers as $offer){
            dd('bbb');
            $job_sent_warning   =   JobSentWarning::where('job_id', $offer->id)
                                        ->where('type', 'offer')
                                        ->where('deleted', 0)
                                        ->first();
            if(!isset($job_sent_warning)){
                echo 'offer warning';
                JobSentWarning::updateOrCreate(
                    [
                        'job_id' => $offer->id,
                        'type'   => 'offer'
                    ],
                    [
                        'deleted' => 0
                    ]
                );
            }
            

        }

    }
}