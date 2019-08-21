<?php

namespace App\Http\Requests;

use App\Agencia;
use Illuminate\Foundation\Http\FormRequest;

class SaveAgenciaRequest extends FormRequest
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
            'nombre_agencia.required' =>"El compo nombre es obligatorio",
            'rfc.required' =>"El compo rfc es obligatorio",
            'rfc.regex' =>"El compo rfc tiene que cumplir con el formato",
            'rfc.unique' =>"El rfc que ingresaste ya esta en uso",
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
            "nombre_agencia" => ["required"],
            "rfc" => ["required", "regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])([A-Z]|[0-9]){2}([A]|[0-9]){1})?$/","unique:agencias"],
        ];
    }


    public function save(){
        $d = $this->validated();
        $data['nombre'] = $d['nombre_agencia'];
        $data['rfc'] = $d['rfc'];

        $agen = Agencia::create($data);

    }
}
