<?php

namespace App\Http\Requests\CondicionFinanciera;

use Illuminate\Foundation\Http\FormRequest;

class StoreCondicionFinancieraRequests extends FormRequest{
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
                'required',
                'exists:productos,id',
            ],
            'moneda_id' => [
                'required',
                'exists:monedas,id',
            ],
            'linea_cliente' => [
                'required',
                'numeric',
                'min:0',
            ],
            'porcentaje_anticipo' => [
                'required',
                'numeric',
                'between:0,100',
            ],
            'monto_comision' => [
                'required',
                'numeric',
                'min:0',
            ],
            'tasa_tem' => [
                'required',
                'numeric',
                'min:0',
            ],
            'adelanto' => [
                'required',
                'numeric',
                'min:0',
            ],
            'linea_adelanto' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }
    public function messages(): array{
        return [
            'tipo_documento_id.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento_id.exists' => 'El tipo de documento seleccionado no existe.',

            'producto_id.required' => 'El producto es obligatorio.',
            'producto_id.exists' => 'El producto seleccionado no es válido.',

            'moneda_id.required' => 'La moneda es obligatoria.',
            'moneda_id.exists' => 'La moneda seleccionada no es válida.',

            'linea_cliente.required' => 'La línea de cliente es obligatoria.',
            'linea_cliente.numeric' => 'La línea de cliente debe ser un número.',
            'linea_cliente.min' => 'La línea de cliente no puede ser negativa.',

            'porcentaje_anticipo.required' => 'El porcentaje de anticipo es obligatorio.',
            'porcentaje_anticipo.numeric' => 'El porcentaje de anticipo debe ser un número.',
            'porcentaje_anticipo.between' => 'El porcentaje de anticipo debe estar entre 0 y 100.',

            'monto_comision.required' => 'El monto de comisión es obligatorio.',
            'monto_comision.numeric' => 'El monto de comisión debe ser un número.',
            'monto_comision.min' => 'El monto de comisión no puede ser negativo.',

            'tasa_tem.required' => 'La tasa TEM es obligatoria.',
            'tasa_tem.numeric' => 'La tasa TEM debe ser un número.',
            'tasa_tem.min' => 'La tasa TEM no puede ser negativa.',

            'adelanto.required' => 'El adelanto es obligatorio.',
            'adelanto.numeric' => 'El adelanto debe ser un número.',
            'adelanto.min' => 'El adelanto no puede ser negativo.',

            'linea_adelanto.required' => 'La línea de adelanto es obligatoria.',
            'linea_adelanto.numeric' => 'La línea de adelanto debe ser un número.',
            'linea_adelanto.min' => 'La línea de adelanto no puede ser negativa.',
        ];
    }
}
