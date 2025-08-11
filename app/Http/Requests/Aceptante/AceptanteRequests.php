<?php

namespace App\Http\Requests\Aceptante;

use Illuminate\Foundation\Http\FormRequest;

class AceptanteRequests extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        return [
            'persona_id'               => ['required', 'exists:personas,id'],
            'ruc'                      => ['required', 'string', 'size:11'],
            'razon_social'             => ['required', 'string', 'max:255'],
            'fecha_inicio'             => ['nullable', 'date'],
            'fecha_sentinel'           => ['nullable', 'date'],
            'cliente_situacion'        => ['nullable', 'string', 'max:255'],
            'endeud_bancario'          => ['nullable', 'numeric', 'min:0'],
            'prorestos'                => ['nullable', 'boolean'],
            'protestos'                => ['nullable', 'integer', 'min:0'],
            'rl_nombre'                => ['nullable', 'string', 'max:255'],
            'situacion_sf'             => ['nullable', 'string', 'max:255'],
            'edad'                     => ['nullable', 'integer', 'min:0'],
            'top'                      => ['nullable', 'string', 'max:255'],
            'ventas_aprox_pagina_web'  => ['nullable', 'numeric', 'min:0'],
            'entidades_apefact'        => ['nullable', 'string', 'max:255'],
            'endeud_apfec'             => ['nullable', 'numeric', 'min:0'],
            'endeud_prom'              => ['nullable', 'numeric', 'min:0'],
        ];
    }
    public function messages(): array{
        return [
            'persona_id.required'  => 'Debe asociar una persona.',
            'persona_id.exists'    => 'La persona seleccionada no existe.',
            'ruc.required'         => 'El RUC es obligatorio.',
            'ruc.size'             => 'El RUC debe tener exactamente 11 dígitos.',
            'razon_social.required'=> 'La razón social es obligatoria.',
            'fecha_inicio.date'    => 'La fecha de inicio no es válida.',
            'fecha_sentinel.date'  => 'La fecha de Sentinel no es válida.',
            'endeud_bancario.numeric' => 'El endeudamiento bancario debe ser numérico.',
            'prorestos.boolean'    => 'El campo prorestos debe ser verdadero o falso.',
            'protestos.integer'    => 'El número de protestos debe ser un entero.',
            'edad.integer'         => 'La edad debe ser un número entero.',
            'ventas_aprox_pagina_web.numeric' => 'Las ventas aproximadas deben ser numéricas.',
            'endeud_apfec.numeric' => 'El endeudamiento APEFAC debe ser numérico.',
            'endeud_prom.numeric'  => 'El endeudamiento promedio debe ser numérico.',
        ];
    }
}
