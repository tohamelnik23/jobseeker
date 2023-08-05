<?php

namespace App\Http\Controllers\Employer\Media;
use App\Http\Controllers\Controller;
use App\User;
use Auth, Storage;
use File;
use Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Media;
use App\Model\Verification;
use App\Model\UsersAccount;

class MediaController extends Controller {
	/**
     * 
     * Select Role
     */ 
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
	public function store(Request $request){
		$tag = $request->input('tag')?$request->input('tag'):''; 
		if($tag=="avtar"):
			if($request->hasFile('file')){
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
				return response()->json([
					'error'			=>	false,
					'messages'		=>	"The avtar files has been updated successfully."
				]);
			}
		endif;
		if($tag=="carpicture"):
			/*
			if($request->hasFile('file')){
				$image 		=  $request->file('file');
				$path  		=  public_path('pictureofcar/');
				$extension  =  $image->getClientOriginalExtension();
				$name 		=  strtolower(uniqid().time().str_random(10).'.'.$extension);
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
			/*
			if($request->hasfile('file')){
				$names 			= array();
				$original_name  = array(); 
				foreach($request->file('file') as $image){
					$path  		 =  public_path('/address/');
					$extension   =  $image->getClientOriginalExtension();
					$name 		 =  strtolower(uniqid().time().str_random(10).'.'.$extension);
					$image->move($path, $name);
					
					$media = new Media;
					$media->path=url('/address').'/'.$name;
					$media->name=$name;
					$media->user_id=$request->user_id;
					$media->type="address";
					$media->status="active";
					$media->is_default="1";
					$media->save();
					
					$verification = new Verification;
					$verification->path=url('/address').'/'.$name;
					$verification->user_id=$request->user_id;
					$verification->type="address";
					$verification->status="new";
					$verification->extension=$extension;
					$verification->save(); 
					$original_name[]= $image->getClientOriginalName();
					$names[]=$name;
				}
				
				$user= User::find($request->user_id);
				$user->address_verified_status = '1';
				$user->save(); 
				return response()->json([
					'name'          => $names,
					'original_name' => $original_name,
					'error'=>false,	
					'messages'=>"Your document to verify address has been uploaded successfully."
				]);
			}
			*/
		endif;
		if($tag=="picture"):
			/*
			if($request->hasfile('file')){
				$names=array();
				$original_name=array(); 
				foreach($request->file('file') as $image) {
					$path  		 =  public_path('/proilevarification/');
					$extension  =$image->getClientOriginalExtension();
					$name 		= strtolower(uniqid().time().str_random(10).'.'.$extension);
					$image->move($path, $name); 
					$parent_id= $request->category_id; 
					$media = new Media;
					$media->path=url('/proilevarification').'/'.$name;
					$media->name=$name;
					$media->user_id=$request->user_id;
					$media->type="picture";
					$media->status="active";
					$media->is_default="1";
					$media->save(); 
					$verification = new Verification;
					$verification->path=url('/proilevarification').'/'.$name;
					$verification->user_id=$request->user_id;
					$verification->type="picture";
					$verification->status="new";
					$verification->extension=$extension;
					$verification->save(); 
					$original_name[]= $image->getClientOriginalName();
					$names[]=$name; 
				} 
				$user= User::find($request->user_id);
				$user->profile_pic_verified_status = '1';
				$user->save(); 
				return response()->json([
					'name'          => $names,
					'original_name' => $original_name,
					'error'=>false,	
					'messages'=>"Your document to verify profile picture has been uploaded successfully."
				]);
			}
			*/
		endif;
	}
}