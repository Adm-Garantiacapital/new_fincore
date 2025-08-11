<?php

namespace App\Http\Requests\CarnetExtrangeria;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarnetExtrangeriaRequests extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        return [
            'documento'        => 'required|string|max:50|unique:carnets_de_extranjeria,documento',
            'nombre'           => 'nullable|string|max:255',
            'apellido_paterno' => 'nullable|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'nacionalidad'     => 'nullable|string|max:100',
            'sexo'             => 'nullable|string|max:20',
            'estado_civil'     => 'nullable|string|max:50',
            'telefono'         => 'nullable|string|max:20',
            'imagenes'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pagina_web'       => 'nullable|url|max:255',
            'direccion'        => 'nullable|string|max:255',
            'persona_id'       => 'nullable|exists:personas,id',
            'created_by'       => 'nullable|exists:users,id',
            'updated_by'       => 'nullable|exists:users,id',
            'deleted_by'       => 'nullable|exists:users,id',
        ];
    }
    public function messages(): array{
        return [
            'documento.required' => 'El documento es obligatorio.',
            'documento.string'   => 'El documento debe ser texto.',
            'documento.max'      => 'El documento no puede exceder 50 caracteres.',
            'documento.unique'   => 'El documento ya está registrado.',
            
            'nombre.string'      => 'El nombre debe ser texto.',
            'nombre.max'         => 'El nombre no puede exceder 255 caracteres.',

            'apellido_paterno.string' => 'El apellido paterno debe ser texto.',
            'apellido_paterno.max'    => 'El apellido paterno no puede exceder 255 caracteres.',

            'apellido_materno.string' => 'El apellido materno debe ser texto.',
            'apellido_materno.max'    => 'El apellido materno no puede exceder 255 caracteres.',

            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',

            'nacionalidad.string' => 'La nacionalidad debe ser texto.',
            'nacionalidad.max'    => 'La nacionalidad no puede exceder 100 caracteres.',

            'sexo.string' => 'El sexo debe ser texto.',
            'sexo.max'    => 'El sexo no puede exceder 20 caracteres.',

            'estado_civil.string' => 'El estado civil debe ser texto.',
            'estado_civil.max'    => 'El estado civil no puede exceder 50 caracteres.',

            'telefono.string' => 'El teléfono debe ser texto.',
            'telefono.max'    => 'El teléfono no puede exceder 20 caracteres.',

            'imagenes.image' => 'La imagen debe ser un archivo válido.',
            'imagenes.mimes' => 'La imagen debe ser de tipo: jpg, jpeg o png.',
            'imagenes.max'   => 'La imagen no puede exceder 2 MB.',

            'pagina_web.url' => 'La página web debe ser una URL válida.',
            'pagina_web.max' => 'La página web no puede exceder 255 caracteres.',

            'direccion.string' => 'La dirección debe ser texto.',
            'direccion.max'    => 'La dirección no puede exceder 255 caracteres.',

            'persona_id.exists'   => 'La persona seleccionada no existe.',

            'created_by.exists' => 'El usuario creador no existe.',
            'updated_by.exists' => 'El usuario actualizador no existe.',
            'deleted_by.exists' => 'El usuario eliminador no existe.',
        ];
    }
}
