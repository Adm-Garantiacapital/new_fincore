<?php

namespace App\Http\Requests\Persona;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaCondicionFinancieraRequests extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        return [
            'persona_id'         => 'nullable|exists:personas,id',
            'linea_cliente'      => 'nullable|numeric|min:0',
            'porcentaje_anticipo'=> 'nullable|numeric|min:0|max:100',
            'monto_comision'     => 'nullable|numeric|min:0',
            'tasa_tem'           => 'nullable|numeric|min:0',
            'adelanto'           => 'nullable|numeric|min:0',
            'linea_adelanto'     => 'nullable|numeric|min:0',
            'moneda_id'          => 'required|exists:monedas,id',
        ];
    }
    public function messages(): array{
        return [
            'persona_id.exists'   => 'La persona seleccionada no existe.',

            'linea_cliente.numeric' => 'La línea de cliente debe ser un valor numérico.',
            'linea_cliente.min'     => 'La línea de cliente no puede ser negativa.',

            'porcentaje_anticipo.numeric' => 'El porcentaje de anticipo debe ser un número.',
            'porcentaje_anticipo.min'     => 'El porcentaje de anticipo no puede ser negativo.',
            'porcentaje_anticipo.max'     => 'El porcentaje de anticipo no puede ser mayor a 100.',

            'monto_comision.numeric' => 'El monto de comisión debe ser un valor numérico.',
            'monto_comision.min'     => 'El monto de comisión no puede ser negativo.',

            'tasa_tem.numeric' => 'La tasa TEM debe ser un valor numérico.',
            'tasa_tem.min'     => 'La tasa TEM no puede ser negativa.',

            'adelanto.numeric' => 'El adelanto debe ser un valor numérico.',
            'adelanto.min'     => 'El adelanto no puede ser negativo.',

            'linea_adelanto.numeric' => 'La línea de adelanto debe ser un valor numérico.',
            'linea_adelanto.min'     => 'La línea de adelanto no puede ser negativa.',

            'moneda_id.required' => 'Debe seleccionar una moneda.',
            'moneda_id.exists'   => 'La moneda seleccionada no existe.',
        ];
    }
}
