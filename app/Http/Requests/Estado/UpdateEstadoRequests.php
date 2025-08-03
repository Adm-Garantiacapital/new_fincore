<?php

namespace App\Http\Requests\Estado;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstadoRequests extends FormRequest{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'nombre' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('estados', 'nombre')->ignore($this->route('id'))
            ],
            'descripcion' => 'nullable|string',
        ];
    }
    public function messages(){
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max' => 'El campo nombre no debe superar los 255 caracteres.',
            'nombre.unique' => 'Ya existe un estado con este nombre.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }
}
