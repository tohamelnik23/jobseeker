<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['question_id','answer','user_id','job_id'];
    protected $table = "answers";

   
}
