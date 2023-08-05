<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Verification extends Model{
    protected $table        = "verification_details"; 
    public function getPath(){
        if($this->extension == 'pdf')
            return asset('address/pdf.png');
        else
            return Storage::disk('spaces')->url( $this->path);  
    }
} 