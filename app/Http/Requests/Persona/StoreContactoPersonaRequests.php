<?php

namespace App\Http\Requests\Persona;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactoPersonaRequests extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        return [
            'dni'              => 'required|string|max:8',
            'nombre'           => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'cargo'            => 'required|string|max:255',
            'correo'            => 'required|email|max:255',
            'telefono'         => 'required|string|max:20',
            'pagina_web'        => 'required|url|max:255',
            'observacion'    => 'required|string',
        ];
    }
    public function messages(): array{
        return [
            'dni.required'              => 'El DNI es obligatorio.',
            'dni.string'                => 'El DNI debe ser un texto.',
            'dni.max'                   => 'El DNI no debe exceder los 8 caracteres.',
            
            'nombre.required'           => 'El nombre es obligatorio.',
            'nombre.string'             => 'El nombre debe ser un texto.',
            'nombre.max'                => 'El nombre no debe exceder los 255 caracteres.',

            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_paterno.string'   => 'El apellido paterno debe ser un texto.',
            'apellido_paterno.max'      => 'El apellido paterno no debe exceder los 255 caracteres.',

            'apellido_materno.required' => 'El apellido materno es obligatorio.',
            'apellido_materno.string'   => 'El apellido materno debe ser un texto.',
            'apellido_materno.max'      => 'El apellido materno no debe exceder los 255 caracteres.',

            'cargo.required'            => 'El cargo es obligatorio.',
            'cargo.string'              => 'El cargo debe ser un texto.',
            'cargo.max'                 => 'El cargo no debe exceder los 255 caracteres.',

            'correo.required'            => 'El correo electrónico es obligatorio.',
            'correo.email'               => 'El correo electrónico debe tener un formato válido.',
            'correo.max'                 => 'El correo electrónico no debe exceder los 255 caracteres.',

            'telefono.required'         => 'El teléfono es obligatorio.',
            'telefono.string'           => 'El teléfono debe ser un texto.',
            'telefono.max'              => 'El teléfono no debe exceder los 20 caracteres.',

            'pagina_web.required'        => 'El sitio web es obligatorio.',
            'pagina_web.url'              => 'El sitio web debe ser una URL válida.',
            'pagina_web.max'              => 'El sitio web no debe exceder los 255 caracteres.',

            'observacion.required'    => 'Las observaciones son obligatorias.',
            'observacion.string'      => 'Las observaciones deben ser texto.',
        ];
    }
}
