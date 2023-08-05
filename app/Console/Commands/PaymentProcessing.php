<?php 
namespace App\Console\Commands; 
use Illuminate\Console\Command;
use Config, Mail, DB;
use Carbon\Carbon; 
use App\Model\DiscovergigTransaction;
class PaymentProcessing extends Command{
    protected $description 	= 'This is for pending to available.';
    protected $signature 	= 'payment:processing'; 
    public function __construct(){
        parent::__construct();
    }
    public function handle(){
        $now =  Carbon::now()->subDays(5);  
        $discovergigs =     DiscovergigTransaction::where('created_at',  '<=', $now->toDateTimeString())
                                                   ->where('status', 'pending')
                                                   ->update([
                                                        'status' => 'available'
                                                   ]);
        //escrow
    }
}