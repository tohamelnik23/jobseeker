<?php 
namespace App\Http\Controllers\master;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Industry;
use App\Model\SubCategory;
use App\Http\Controllers\BaseController;
class IndustriesController extends BaseController {
    public function index() {
		$view 				=	array();
		$view['user'] 		=	Auth::user();
		$view['industries'] =   Industry::where('deleted', 0)
										->get();
		return view('master.industries.industries', $view);
    } 
	public function delete(Request $request, $id){
		$industry 	= 	Industry::where('deleted', 0)
							->where('id', $id)
							->first();
		if(!isset($industry))
			abort(404);
		$industry->update([
			'deleted' => 1
		]);
		return redirect()->route('master.industries')->with('success', "The job type " . $industry->industry . " has been removed successfully");
	} 
	 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function edit(Request $request)
    { 
		$industry 	= Industry::where('serial' , $request->id)->first(); 
		if(isset($industry)){ 
			$viewdata = view('master.industries.edit')->with(array('industry'=>$industry))->render();
			return response()->json(['error'=>false,'title'=> $industry->industry,'viewdata'=>$viewdata]);	
		}
		else{
			return response()->json(['error'=>true,'title'=> "Invalid serial"]);	
		}	
    } 
	public function add(Request $request) {
        $rules = array(
			'industry' => 'required' 
        );
        $input_arr = array(
			'industry' => $request->input('industry')          
        );  
		$message= array(
			'industry.required'=>'Please provide industry name.' 
		);
        $validator = Validator::make($input_arr,$rules,$message); 
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:  
			$industry = new Industry;
			$industry->industry = $request->input('industry');
			$industry->save(); 
			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif;  
    } 
	public function update(Request $request){
        $rules = array(
			'industry' 	=> 'required',
			'id' 		=> 'required' 
        );
        $input_arr = array(
			'industry' => $request->input('industry'),        
			'id' => $request->input('id')          
        );  
		$message= array(
			'industry.required'=>'Please provide industry name.',
			'id.required'=>'id is required.' 
		);
        $validator = Validator::make($input_arr,$rules,$message);

        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:   
			$industry 	= 	Industry::where('serial', $request->id)
									->first();
			if(isset($industry)){
				$industry->industry = $request->industry; 
				$industry->save(); 
				return response()->json(['error'=>false,'success'=>'Added successfully!']);
			}
			else{
				return response()->json(['error'=>true,'success'=> 'Invalid Industry']);
			} 
		endif;  
    }
	public function subcategories(Request $request, $id){
		$industry 	= 	Industry::where('deleted', 0)
							->where('serial', $id)
							->first();
		if(!isset($industry))
			abort(404); 
		$this->industry 		= 	$industry;
		$this->subcategoreis 	= 	SubCategory::where('category_id', $industry->id)
										->where('deleted', 0)
										->paginate(10);
		$this->industries 		=	Industry::where('deleted', 0)
										->get();
		return view('master.industries.subcategories', $this->data);
	}
	public function subcategories_add(Request $request, $id) {
        $rules = array(
			'subcategory' => 'required'
        );
        $input_arr = array(
			'subcategory' => $request->input('subcategory')          
        );  
		$message= array(
			'subcategory.required'=>'Please provide subcategory name.' 
		);
        $validator = Validator::make($input_arr,$rules,$message); 
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:  
			$industry 	= 	Industry::where('deleted', 0)
								->where('serial', $id)
								->first();
			if(!isset($industry)){
				return response()->json(['error'=>true,'errors'=>['industry' => 'Invalid Industry']]);
			} 
			SubCategory::create([
				'category_id' => $industry->id,
				'name'		  => $request->subcategory
			]);
			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif;  
    }
	public function subcategories_delete(Request $request, $sub_id){
		$subcategory 	= 	SubCategory::where('deleted', 0)
									->where('serial', $sub_id)
									->first();
		if(!isset($subcategory))
			abort(404);
		$subcategory->update([
			'deleted' => 1
		]);
		return redirect()->route('master.industries')->with('success', "The sub category " . $subcategory->name . " has been removed successfully");
		//return redirect()->route('master.industries.subcategories', $industry->serial)->with('success', "The sub category " . $subcategory->name . " has been removed successfully");
	}
	public function subcategories_edit(Request $request){ 
		$subcategory 	= 	SubCategory::where('deleted', 0)
									->where('serial', $request->id)
									->first();
		if(!isset($subcategory))
			return response()->json(['error'=>true,'message'=> "Invalid subcategory" ]);  
		$industry 	=  	$subcategory->getCategory();
		$viewdata	= 	view('master.industries.subcategory_edit')->with( array('subcategory' => $subcategory, 'industry' => $industry) )->render();

		return response()->json(['error'=>false,'title'=> $subcategory->name,'viewdata'=>$viewdata]);
    }
	public function subcategories_update(Request $request){
        $rules = array(
			'subcategory' 	=> 'required',
			'id' 			=> 'required' 
        );
        $input_arr = array(
			'subcategory' 	=> $request->input('subcategory'),        
			'id' 			=> $request->input('id')          
        );  
		$message= array(
			'subcategory.required'=>'Please provide sub category name.',
			'id.required'=>'id is required.' 
		);
        $validator = Validator::make($input_arr,$rules,$message);
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:
			$subcategory 		= 	SubCategory::where('deleted', 0)
										->where('serial', $request->id)
										->first();	  
			if(!isset($subcategory))
				return response()->json(['error'=>true,'message'=> "Invalid Sub Category" ]);
			$subcategory->name 	= 	$request->input('subcategory');
			$subcategory->save(); 
			return response()->json(['error'=>false,'success'=>'Updated successfully!']);
		endif;  
    }	
}