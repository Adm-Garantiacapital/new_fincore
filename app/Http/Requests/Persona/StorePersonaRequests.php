<?php

namespace App\Http\Requests\Persona;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaRequests extends FormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            'tipo_documento_id' => [
                'required',
                'exists:tipos_documento,id',
            ],
            'producto_id' => [
                'nullable',
                'exists:productos,id',
            ],
            'observacion' => [
                'required',
                'string',
                'max:1000',
            ],
            'comentario' => [
                'required',
                'string',
                'max:1000',
            ],
            'moneda_id' => [
                'required',
                'exists:monedas,id',
            ],
        ];
    }

    public function messages(): array{
        return [
            'tipo_documento_id.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento_id.exists' => 'El tipo de documento seleccionado no existe.',
            'producto_id.exists' => 'El producto seleccionado no es válido.',

            'observacion.required' => 'La observación es obligatoria.',
            'observacion.string' => 'La observación debe ser texto válido.',
            'observacion.max' => 'La observación no puede tener más de 1000 caracteres.',

            'comentario.required' => 'El comentario es obligatorio.',
            'comentario.string' => 'El comentario debe ser texto válido.',
            'comentario.max' => 'El comentario no puede tener más de 1000 caracteres.',

            'moneda_id.required' => 'La moneda es obligatoria.',
            'moneda_id.exists' => 'La moneda seleccionada no es válida.',

        ];
    }
}
