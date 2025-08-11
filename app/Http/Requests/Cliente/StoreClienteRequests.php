<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequests extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        return [
            'dni' => ['required', 'digits:8'],
            'nombre' => ['required', 'regex:/^[\pL\s]+$/u'],
            'apellido_paterno' => ['required', 'regex:/^[\pL\s]+$/u'],
            'apellido_materno' => ['required', 'regex:/^[\pL\s]+$/u'],
            'direccion' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date'],
            'sexo' => ['required', 'string'],
            'estado_civil' => ['required', 'regex:/^[\pL\s]+$/u'],
            'pagina_web' => ['required', 'url'],
            'telefono' => ['required', 'regex:/^[0-9]+$/'],
            'persona_id' => ['nullable', 'integer', 'exists:personas,id'],
        ];
    }
    public function messages(): array{
        return [
            'dni.required' => 'El campo DNI es obligatorio.',
            'dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',

            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',

            'apellido_paterno.required' => 'El campo apellido paterno es obligatorio.',
            'apellido_paterno.regex' => 'El apellido paterno solo puede contener letras y espacios.',

            'apellido_materno.required' => 'El campo apellido materno es obligatorio.',
            'apellido_materno.regex' => 'El apellido materno solo puede contener letras y espacios.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento no tiene un formato válido.',

            'sexo.required' => 'El campo sexo es obligatorio.',

            'estado_civil.required' => 'El campo estado civil es obligatorio.',
            'estado_civil.regex' => 'El estado civil solo puede contener letras y espacios.',

            'pagina_web.required' => 'La página web es obligatoria.',
            'pagina_web.url' => 'La página web debe tener un formato válido.',

            'telefono.required' => 'El número de teléfono es obligatorio.',
            'telefono.regex' => 'El número de teléfono solo puede contener números.',

            'comisiones.required' => 'El campo comisiones es obligatorio.',
            'comisiones.numeric' => 'El campo comisiones debe ser un número decimal.',

            'tasa_esperada.required' => 'El campo tasa esperada es obligatorio.',
            'tasa_esperada.numeric' => 'El campo tasa esperada debe ser un número decimal.',

            'producto_id.required' => 'Debe seleccionar un producto.',
            'producto_id.integer' => 'El ID del producto debe ser un número entero.',
            'producto_id.exists' => 'El producto seleccionado no existe en la base de datos.',
        ];
    }
}
