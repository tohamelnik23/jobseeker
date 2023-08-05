<?php 
namespace App\Http\Controllers\master;
use App\Http\Controllers\BaseController;
use App\User;
use Auth;
use Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Question; 
class QuestionsController extends BaseController {
    public function index(){
        $this->questions    =   Question::where('deleted', 0)
                                    ->where('type', '1')
                                    ->paginate(10);    
		return view('master.questions.questions',$this->data);
    }
    public function add(Request $request){
        $rules = array(
			'question' => 'required'
        );
        $input_arr = array(
			'question' => $request->input('question')          
        );  
		$message= array(
			'question.required'=>'Please provide question.' 
		);
        $validator = Validator::make($input_arr,$rules,$message);
        if($validator->fails()):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:
			$question              = new Question;
            $question->question    = $request->input('question');
            $question->type        = 1;
            $question->user_id     = Auth::user()->id;
			$question->save(); 
			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif;  
    }
    public function edit(Request $request){  
        $question   =  Question::where('serial', $request->id)
                            ->first();
        if(!isset($question))    
            return response()->json(['error'=>true,'title'=> $question->question]); 
        $viewdata   = view('master.questions.edit')->with(array('question'=>$question))->render(); 
		return response()->json(['error'=>false,'title'=> $question->question,'viewdata'=>$viewdata]);
    }
    public function update(Request $request){ 
        $rules = array(
			'question'  => 'required',
			'id' 	    => 'required'
        );
        $input_arr = array(
			'question' => $request->input('question'),        
			'id' => $request->input('id')          
        );  
		$message= array(
			'question.required'=>'Please provide question name.',
			'id.required'=>'id is required.'
		);
        $validator = Validator::make($input_arr,$rules,$message); 
        if( $validator->fails() ):  
			return response()->json(['error'=>true,'errors'=>$validator->errors()]);
        else:   
            $question           =   Question::where('serial', $request->id)
                                            ->first(); 
            if(!isset($question))
                return response()->json(['error'=>true,'title'=> $question->question]);  
			$question->question = $request->input('question');
			$question->save(); 
			return response()->json(['error'=>false,'success'=>'Added successfully!']);
		endif;
    }
}