<?php
namespace App\Http\Controllers\Employee\Media;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use File;
use Session, Storage;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Media;
use App\Model\Verification;
use App\Model\UsersAccount;
use App\Model\Notification;
use App\Model\DriverLicense;

class MediaController extends Controller {
    public function delete(Request $request) {
	   
		$tag = $request->input('tag')?$request->input('tag'):'';
		if($tag=="removeAvtar"):
			$path  = public_path('/avatar/');
				$mediaexists = Media::where(array('user_id' =>$request->user_id,'type'=>'avtar'))->first();
				if(!empty($mediaexists) && count($mediaexists)>0):
					if (File::exists($path.$mediaexists->name) ) {
						//File::delete($path.$mediaexists->name);
					} 
					//Media::where('id',$mediaexists->id)->delete();
					$user= UsersAccount::find($request->user_id);
					$user->profilepic = 'placeholderAvatar.jpg';
					$user->save();
				endif;
				
		endif;
	}
	public function store(Request $request) { 
		$tag = $request->input('tag')?$request->input('tag'):''; 
		if($tag=="avtar"):
			if($request->hasFile('file') ){ 
				$user	  		= UsersAccount::find(Auth::user()->id); 
				$extension      = $request->file('file')->getClientOriginalExtension();
				$avatar         = strtolower( $user->id . time() . '.'.$extension ); 
				Storage::disk('spaces')->put( 'avatar/' . $avatar, file_get_contents($request->file('file')->getRealPath()), 'public'); 
				Media::updateOrCreate([
					'user_id'		=> Auth::user()->id,
					'type'			=> 'avtar'
				],[
					'path' 			=>  'avatar/' . $avatar,
					'name'			=>   $avatar,
					'status'		=>  "active",
					'is_default'	=>   1
				]);  
				$user->profilepic =  'avatar/' . $avatar;
				$user->save();
				return response()->json([ 
					'error'			=>	false,
					'messages'		=>	"The avtar files has been updated successfully."
				]);
			}
		endif;
		if($tag=="carpicture"):
			/*
			if($request->hasFile('file') ){
				$image 		= $request->file('file');
				$path  		 =  public_path('pictureofcar/');
				$extension  =$image->getClientOriginalExtension();
				$name 		= strtolower(uniqid().time().str_random(10).'.'.$extension);
				$image->move($path, $name); 
				$media = new Media;
				$media->path=url('/pictureofcar/').'/'.$name;
				$media->name=$name;
				$media->user_id=$request->user_id;
				$media->type="carpicture";
				$media->status="active";
				$media->is_default="1";
				$media->save();
				return response()->json([
					'name'          => $name,
					'id'            => $media->id,
					'original_name' => $image->getClientOriginalName(),
					'error'=>false,
					'messages'=>"The files has been updated successfully."
				]);
			}
			*/
		endif;
		if($tag=="proofofinsurance"):
			/*
			if($request->hasFile('file') ){
				$image 		= $request->file('file');
				$path  		 =  public_path('proofofinsurance/');
				$extension  =$image->getClientOriginalExtension();
				$name 		= strtolower(uniqid().time().str_random(10).'.'.$extension);
				$image->move($path, $name);
				
				$media = New Media;
				$media->path=url('/proofofinsurance/').'/'.$name;
				$media->name=$name;
				$media->user_id=$request->user_id;
				$media->type="proofofinsurance";
				$media->status="active";
				$media->is_default="1";
				$media->save();
				return response()->json([
					'name'          => $name,
					'id'            => $media->id,
					'original_name' => $image->getClientOriginalName(),
					'error'=>false,
					'messages'=>"The files has been updated successfully."
				]);
			}
			*/
		endif;
		if($tag=="address"):   
			if($request->hasfile('file')){ 
				$rules = array(
					'caddress'              => 'required',
					'city'              	=> 'required',
					'state'               	=> 'required',
					'zip'            		=> 'required', 
				);
				$input_arr = array(
					'caddress'            => $request->input('caddress'),
					'city'                => $request->input('city'),
					'state'               => $request->input('state'),
					'zip'                 => $request->input('zip'),
					'lat'                 => $request->input('lat'),
					'lng'                 => $request->input('lng'),
					'oaddress'            => $request->input('oaddress'), 
				);
				$message= array(
					'caddress.required'=>'Please provide your Address.',
					'city.required'=>'Please provide your city.',
					'state.required'=>'Please provide your state.',
					'zip.required'=>'Please provide a valid zip.' 
				);
				$validator = Validator::make($input_arr,$rules,$message); 
				if( $validator->fails() ):  
					return response()->json(['error'=>true,'errors'=>$validator->errors()]);
				else: 
					$old_user 		= UsersAccount::find(Auth::user()->id)->toArray();
					$user 			= UsersAccount::find(Auth::user()->id);
					$user->caddress = $request->input('caddress');
					$user->city 	= $request->input('city');
					$user->state 	= $request->input('state');
					$user->zip 		= $request->input('zip');
					$user->lat 		= $request->input('lat');
					$user->lng 		= $request->input('lng');
					$user->oaddress = $request->input('oaddress');
					$user->save(); 
					$uh = new User(); 
					$uh->Updatehistory($old_user,$input_arr); 
					if($request->has('profile_start')){
						$user->start_stage = 1;
						$user->save();
					}  
				endif;

				$names 			=	array();
				$original_name	=	array(); 
				$user 			= 	Auth::user(); 
				foreach($request->file('file') as $image){ 
					$extension  = 	$image->getClientOriginalExtension();
					$name 		= 	strtolower(uniqid().time(). time(). '.'.$extension); 
					Storage::disk('spaces')->put( 'address/' . $user->id . '/' . $name,  file_get_contents($image->getRealPath()), 'public'); 
					
					$media 					= 	 new Media;
					$media->path			=	 'address/' .  $user->id . '/' . $name;
					$media->name    		=	 $name;
					$media->user_id 		=    $user->id; 
					$media->type 			=	"address";
					$media->status 			=	"active";
					$media->is_default		=	"1";
					$media->save();
					
					$verification 				=  	new Verification;
					$verification->path			=	'address/' .  $user->id . '/' . $name;
					$verification->user_id 		=	$user->id; 
					$verification->type 		=	"address";
					$verification->status 		=	"new";
					$verification->extension	=	$extension;
					$verification->save();
					
					$original_name[]			= 	$image->getClientOriginalName();
					$names[]   					=	$name; 
				}
				$user->address_verified_status = '1';
				$user->save();
				
				Notification::create([
					'notifications_fromuser' 	=> $user->id,
					'notifications_touser'		=> 0,
					'notifications_value'		=> 'Address Verification Request',
					'notifications_type'		=> 'address_verification'
				]);

				return response()->json([
					'name'          =>	$names,
					'original_name' => 	$original_name,
					'error'			=>	false,	
					'messages'		=>	"Your document to verify address has been uploaded successfully."
				]); 
			} 
		endif;
		if($tag=="picture"):
			if($request->hasfile('file')){ 
				$names 			=	array();
				$original_name 	=	array(); 
				$user 			= 	Auth::user(); 
				foreach($request->file('file') as $image){  
					$extension   =  $image->getClientOriginalExtension(); 
					$name 		= 	strtolower(uniqid().time(). time(). '.'.$extension); 
					Storage::disk('spaces')->put( 'proilevarification/' . $user->id . '/' . $name,  file_get_contents($image->getRealPath()), 'public'); 
					
					$media 					= 	 new Media;
					$media->path			=	 'proilevarification/' .  $user->id . '/' . $name;
					$media->name    		=	 $name;
					$media->user_id 		=    $user->id; 
					$media->type 			=	 "picture";
					$media->status 			=	 "active";
					$media->is_default		=	 "1";
					$media->save();
					
					$verification 				=  	new Verification;
					$verification->path			=	'proilevarification/' .  $user->id . '/' . $name;
					$verification->user_id 		=	$user->id; 
					$verification->type 		=	"picture";
					$verification->status 		=	"new";
					$verification->extension	=	$extension;
					$verification->save();

					$original_name[] 			=   $image->getClientOriginalName();
					$names[] 					= 	$name;
				}
				
				$user 								= 	User::find($request->user_id);
				$user->profile_pic_verified_status 	= 	'1';
				$user->save();
				
				Notification::create([
					'notifications_fromuser' 	=> $user->id,
					'notifications_touser'		=> 0,
					'notifications_value'		=> 'Profile Picture  Verification Request',
					'notifications_type'		=> 'profile_verification'
				]); 
				return response()->json([
					'name'          => $names,
					'original_name' => $original_name,
					'error'			=> false,	
					'messages'		=> "Your document to verify profile picture has been uploaded successfully."
				]);
			 } 
		endif;
		if($tag=="driverlicense"):  
			if($request->hasfile('file')){
				$rules = array(
					'driver_state'            => 'required',
					'plate_number'            => 'required',
					'expiration_year'         => 'required',
					'expiration_month'        => 'required', 
				);
				$input_arr = array(
					'driver_state'            => $request->input('driver_state'),
					'plate_number'            => $request->input('plate_number'),
					'expiration_year'         => $request->input('expiration_year'),
					'expiration_month'        => $request->input('expiration_month'), 
				);
				$message= array(
					'driver_state.required'=>'Please provide the state.',
					'plate_number.required'=>'Please provide the number.',
					'expiration_year.required'=>'Please provide the expiration year.',
					'expiration_month.required'=>'Please provide a expiration month.' 
				);
				$validator = Validator::make($input_arr,$rules,$message); 
				if( $validator->fails() ):  
					return response()->json(['error'=>true,'errors'=>$validator->errors()]);
				else: 
					DriverLicense::updateOrCreate([
						'user_id' 			=> Auth::user()->id
					],[
						'plate_number' 		=> strtoupper($request->plate_number),
						'state'				=> $request->driver_state,
						'expiration_year'	=> $request->expiration_year,
						'expiration_month'	=> $request->expiration_month,
					]);  
				endif;

				$names 			=	array();
				$original_name	=	array(); 
				$user 			= 	Auth::user(); 
				foreach($request->file('file') as $image){ 
					$extension  = 	$image->getClientOriginalExtension();
					$name 		= 	strtolower(uniqid().time(). time(). '.'.$extension); 
					Storage::disk('spaces')->put( 'driverlicense/' . $user->id . '/' . $name,  file_get_contents($image->getRealPath()), 'public');  
					$media 					= 	 new Media;
					$media->path			=	 'driverlicense/' .  $user->id . '/' . $name;
					$media->name    		=	 $name;
					$media->user_id 		=    $user->id; 
					$media->type 			=	"driverlicense";
					$media->status 			=	"active";
					$media->is_default		=	"1";
					$media->save(); 
						
					$verification 				=  	new Verification;
					$verification->path			=	'driverlicense/' .  $user->id . '/' . $name;
					$verification->user_id 		=	$user->id; 
					$verification->type 		=	"driverlicense";
					$verification->status 		=	"new";
					$verification->extension	=	$extension;
					$verification->save();
					
					$original_name[]			= 	$image->getClientOriginalName();
					$names[]   					=	$name; 
				}
				
				DriverLicense::updateOrCreate([
					'user_id' 			=> Auth::user()->id
				],[
					'verified'			=>  '1'
				]);

				Notification::create([
					'notifications_fromuser' 	=> $user->id,
					'notifications_touser'		=> 0,
					'notifications_value'		=> 'Driver Ricense Verification Request',
					'notifications_type'		=> 'driverlicense_verification'
				]);

				return response()->json([
					'name'          =>	$names,
					'original_name' => 	$original_name,
					'error'			=>	false,	
					'messages'		=>	"Your document to verify the driver license has been uploaded successfully."
				]);  
			}
		endif;
	} 
}