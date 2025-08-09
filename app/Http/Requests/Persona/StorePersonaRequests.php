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
                'exists:tipos_documento,id'
            ],
            'producto_id' => [
                'nullable',
                'exists:productos,id'
            ]
        ];
    }
    public function messages(): array{
        return [
            'tipo_documento_id.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento_id.exists' => 'El tipo de documento seleccionado no existe.',
            'producto_id.exists' => 'El producto seleccionado no es válido.',
        ];
    }
}
