<?php
namespace App\Console\Commands; 
use Illuminate\Console\Command;
use Config, Mail, DB;
use Carbon\Carbon; 
use App\Model\DiscovergigTransaction;
use App\Model\Escrow;
use App\Model\TimeSheet;
use App\Model\MasterSetting;
use App\Model\Card;
use App\Model\Transaction;
use App\Model\Offer;
use App\Model\Notification;
class ProposalArchieved extends Command{
    protected $description 	= 'This is for hourly job.';
    protected $signature 	= 'proposal:archived'; 
    public function __construct(){
        parent::__construct();
    }
    public function handle(){  
    } 
}