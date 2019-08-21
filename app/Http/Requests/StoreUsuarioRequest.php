<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
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
            'username' => 'required',
            'email' => 'required|unique:usuarios',
            'password' => 'required',
            'usertype_id' => 'required|exists:cat_usertype,id',

        ];
    }



    public function messages()
    {
        return [
            'usertype_id.required' => 'Por favor escoja un tipo de usuario',
            'usertype_id.exists' => 'Por favor escoja un tipo de usuario valido'
        ];
    }


}
