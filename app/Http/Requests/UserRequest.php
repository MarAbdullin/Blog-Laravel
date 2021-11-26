<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $model = $this->route('user');
        
        if($this->change_password){
            return [
                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    // проверка на уникальность email, исключая
                    // этого пользователя по идентифкатору
                    'unique:users,email,'.$model->id.',id',
                ],
                'password' => [
                    'required', 
                    'string', 
                    'min:8', 
                    'confirmed'
                ],
            ];
        }
        else{
            return [
                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    // проверка на уникальность email, исключая
                    // этого пользователя по идентифкатору
                    'unique:users,email,'.$model->id.',id',
                ],
            ];    
        }
    
    }
}
