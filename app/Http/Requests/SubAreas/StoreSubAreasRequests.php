<?php

namespace App\Http\Requests\SubAreas;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubAreasRequests extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        return [
            'nombre' => 'required|string|max:255|unique:sub_areas,nombre',
            'codigo' => 'nullable|string|max:255',
            'area_id' => 'required|exists:areas,id',
            'estado_id' => 'required|exists:estados,id',
        ];
    }
    public function messages(): array{
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max' => 'El campo nombre no debe superar los 255 caracteres.',
            'nombre.unique' => 'Ya existe una subárea con este nombre.',
            'codigo.string' => 'El campo código debe ser una cadena de texto.',
            'codigo.max' => 'El campo código no debe superar los 255 caracteres.',
            'area_id.required' => 'Debe seleccionar un área.',
            'area_id.exists' => 'El área seleccionada no es válida.',
            'estado_id.required' => 'Debe seleccionar un estado.',
            'estado_id.exists' => 'El estado seleccionado no es válido.',
        ];
    }
}
