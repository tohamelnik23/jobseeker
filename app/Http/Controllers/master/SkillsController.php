<?php

namespace App\Http\Controllers\master;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Skill;
class SkillsController extends Controller {
    public function index(){
		$view 			=	array();
		$view['user'] 	=	Auth::user(); 
		$view['skills'] =   Skill::where('deleted', 0)
								->paginate(10); 
		return view('master.skills.skills',$view);
	}

	public function delete(Request $request, $id){
		$skill 	= 	Skill::where('deleted', 0)
							->where('id', $id)
							->first();
		if(!isset($skill))
			abort(404);
		$skill->update([
			'deleted' => 1
		]);
		return redirect()->route('master.skills')->with('success', "The skill " . $skill->skill . " has been removed successfully");
	}
	
	public function list(Request $request){ 
		$skill = new Skill();
		$list= $skill->getSkillList($request,$request->input('length'),$request->input('start')); 
		$data = array();	
		$no = $request->input('start'); 
		foreach ($list['result'] as $res) {   
			$no++; 
			$html="";
			$row = array(); 
			//$editurl = route('master.skills.edit', ['id' =>$res->id]);
			$editurl = '#';
			$row[] = $res->skill;
			$row[] = isset($res->created_at)?$res->created_at->format('m/d/Y'):'';		
			$row[] = '<a class="btn btn-success margin-5" href="#" onclick="editskill('.$res->id.')">Edit</a>';			
			$data[] = $row;    
		} 
		$output = array(      
			"draw" 				=> isset($_REQUEST['draw'])?intval($_REQUEST['draw']):'',
			"recordsTotal" 		=> intval($list['num']),    
			"recordsFiltered" 	=> intval($list['num']),     
			"data" 				=> $data,  
		); 
		echo json_encode($output); 	
	} 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request){ 
		$skill = Skill::find($request->id);
		$viewdata= view('master.skills.edit')->with(array('skill'=>$skill))->render();
		return response()->json(['error'=>false,'title'=> $skill->skill,'viewdata'=>$viewdata]);
    }
	
	public function add(Request $request)
    {
        $rules = array(
			'skill' => 'required'
        );
        $input_arr = array(
			'skill' => $request->input('skill')          
        );  
		$message= array(
			'skill.required'=>'Please provide skill name.' 
		);
        $validator = Validator::make($input_arr,$rules,$message); 
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:
			$skill 			= new Skill;
			$skill->skill 	= $request->input('skill');
			$skill->save();
			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif;  
    }
	public function update(Request $request){ 
        $rules = array(
			'skill' => 'required',
			'id' 	=> 'required'
        );
        $input_arr = array(
			'skill' => $request->input('skill'),        
			'id' => $request->input('id')          
        );  
		$message= array(
			'skill.required'=>'Please provide skill name.',
			'id.required'=>'id is required.' 
		);
        $validator = Validator::make($input_arr,$rules,$message); 
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:  
			$skill = Skill::find($request->id);
			$skill->skill = $request->input('skill');
			$skill->save(); 
			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif;  
    }
}