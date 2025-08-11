<?php

namespace App\Http\Requests\PersonaArchivo;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaArchivo extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        return [
            'persona_id' => [
                'nullable',
                'exists:personas,id'
            ],
            'archivos' => [
                'required',
                'array'
            ],
            'archivos.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
                'max:5120'
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255'
            ]
        ];
    }
    public function messages(): array{
        return [
            'archivos.required' => 'Debes adjuntar al menos un archivo.',
            'archivos.array' => 'Los archivos deben enviarse en formato de lista.',
            'archivos.*.file' => 'Cada archivo debe ser válido.',
            'archivos.*.mimes' => 'Los archivos deben ser de tipo: jpg, jpeg, png, pdf, doc, docx, xls, xlsx.',
            'archivos.*.max' => 'Cada archivo no puede superar los 5 MB.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.'
        ];
    }
}
