<?php

namespace App\Http\Controllers\master;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class DashboardController extends Controller {
	
    public function index() {
		$view=array();
		$view['user']=Auth::user();
		return view('master.dashboard.dashboard',$view);
    } 
	
}