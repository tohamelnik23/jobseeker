<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Illuminate\Contracts\Validation\Validator;
use App\Helpers\Mainhelper;

class Forgetpassword extends FormRequest
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
	
	
	public function messages()
	{
		return [
			'email.required'=>'The email name field is required.',
		//	'email.email'=>'Enter valid email address.',
		];
	}
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'email' => 'required',
        ];
    }
	
	
	public function withValidator($validator)
	{
		$request = request();
		$dataErr['email']=$request->get('email');
		$validator->after(function ($validator)use ($dataErr) {
			$errors = $validator->messages()->getMessages();
			if(empty($errors)):
				if(!empty($dataErr['email'])):
					$data = User::where('email', $dataErr['email'])->first();
					
				 	if(!empty($data)){ 
						/*if($data->users_status == 0){
							$validator->errors()->add('email', 'Your account is not active.');
						}*/
					}
					
					if(empty($data)){ 
						$data =  User::where('cphonenumber',  Mainhelper::removephonenumberspecial($dataErr['email']))->first();
					}
					if(empty($data)){  
						$validator->errors()->add('email', 'This  address does not exist.');
					} 
				endif;
			endif;
		});
	}
}
