<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Route;
use Helper;
class Resetpassword extends FormRequest
{
	
	
	
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'newpassword' => 'required|min:6', 
			'confirmpassword' => 'required|same:newpassword',
        ];
    }
	
	public function messages()
	{
		return [
			'newpassword.required'=>'The new password fields is required',
			'confirmpassword.required'=>'The confirm password fields is required',
			'confirmpassword.same'=>'The password and its confirm are not the same.',
			'newpassword.min'=>'The passwords must be at least 6 characters in length.',
		];
	}
	
	public function withValidator($validator)
	{
		$request = request();
		$dataErr['code']=$request->get('code');
		$dataErr['uid']=$request->get('uid');
		$validator->after(function ($validator)use ($dataErr) {
			$errors = $validator->messages()->getMessages();
			if(empty($errors)):
				$userData = User::where(array('id'=>base64_decode($dataErr['uid']),'token'=>$dataErr['code']))->first();
				if(empty($userData)): 
						$validator->errors()->add('confirmpassword', 'Sorry But this link is expired please try again');
				endif;
			endif;
		});
	}
	
}