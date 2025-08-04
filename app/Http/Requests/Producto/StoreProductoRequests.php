<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequests extends FormRequest{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'nombre' => 'required|string|max:255|unique:productos,nombre',
            'descripcion' => 'nullable|string',
            'estado_id' => 'required|exists:estados,id',
        ];
    }
    public function messages(){
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max' => 'El campo nombre no debe superar los 255 caracteres.',
            'nombre.unique' => 'Ya existe un producto con este nombre.',
            
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',

            'estado_id.required' => 'El campo estado es obligatorio.',
            'estado_id.exists' => 'El estado seleccionado no es válido.',
        ];
    }
}
