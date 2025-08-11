<?php

namespace App\Http\Requests\Detalle;

use Illuminate\Foundation\Http\FormRequest;

class DetallePersonaRequests extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'persona_id' => 'required|exists:personas,id',
            'anio' => 'nullable|integer|min:1900|max:' . date('Y'),
            'nombre_comercial' => 'nullable|string|max:255',
            'fecha_inscripcion' => 'nullable|date',
            'fecha_inicio_actividades' => 'nullable|date',
            'estado_contribuyente' => 'nullable|string|max:255',
            'condicion_contribuyente' => 'nullable|string|max:255',
            'domicilio_fiscal' => 'nullable|string|max:255',
            'actividad_comercio_exterior' => 'nullable|string|max:255',
            'actividad_economica' => 'nullable|string|max:255',
            'ingresos_netos' => 'nullable|numeric|min:0',
            'otros_ingresos' => 'nullable|numeric|min:0',
            'total_activos' => 'nullable|numeric|min:0',
            'total_cuentas_por_pagar' => 'nullable|numeric|min:0',
            'total_pasivo' => 'nullable|numeric|min:0',
            'total_patrimonio' => 'nullable|numeric|min:0',
            'capital_social' => 'nullable|numeric|min:0',
            'resultado_bruto' => 'nullable|numeric|min:0',
            'resultado_antes_imp' => 'nullable|numeric|min:0',
            'importe_pagado' => 'nullable|numeric|min:0',
            'ingreso_12_meses' => 'nullable|numeric|min:0',
            'ingreso_6_meses' => 'nullable|numeric|min:0',
            'promedio_ingreso_12_meses' => 'nullable|numeric|min:0',
            'promedio_ingreso_6_meses' => 'nullable|numeric|min:0',
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
            'deleted_by' => 'nullable|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'persona_id.required' => 'Debe asociar una persona.',
            'persona_id.exists' => 'La persona seleccionada no existe.',
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año no puede ser menor a 1900.',
            'anio.max' => 'El año no puede ser mayor al actual.',
            'nombre_comercial.max' => 'El nombre comercial no puede exceder 255 caracteres.',
            'fecha_inscripcion.date' => 'La fecha de inscripción no es válida.',
            'fecha_inicio_actividades.date' => 'La fecha de inicio de actividades no es válida.',
            'estado_contribuyente.max' => 'El estado del contribuyente no puede exceder 255 caracteres.',
            'condicion_contribuyente.max' => 'La condición del contribuyente no puede exceder 255 caracteres.',
            'domicilio_fiscal.max' => 'El domicilio fiscal no puede exceder 255 caracteres.',
            'actividad_comercio_exterior.max' => 'La actividad de comercio exterior no puede exceder 255 caracteres.',
            'actividad_economica.max' => 'La actividad económica no puede exceder 255 caracteres.',
            'ingresos_netos.numeric' => 'Los ingresos netos deben ser un número.',
            'otros_ingresos.numeric' => 'Los otros ingresos deben ser un número.',
            'total_activos.numeric' => 'El total de activos debe ser un número.',
            'total_cuentas_por_pagar.numeric' => 'El total de cuentas por pagar debe ser un número.',
            'total_pasivo.numeric' => 'El total pasivo debe ser un número.',
            'total_patrimonio.numeric' => 'El total de patrimonio debe ser un número.',
            'capital_social.numeric' => 'El capital social debe ser un número.',
            'resultado_bruto.numeric' => 'El resultado bruto debe ser un número.',
            'resultado_antes_imp.numeric' => 'El resultado antes de impuestos debe ser un número.',
            'importe_pagado.numeric' => 'El importe pagado debe ser un número.',
            'ingreso_12_meses.numeric' => 'El ingreso de 12 meses debe ser un número.',
            'ingreso_6_meses.numeric' => 'El ingreso de 6 meses debe ser un número.',
            'promedio_ingreso_12_meses.numeric' => 'El promedio de ingresos en 12 meses debe ser un número.',
            'promedio_ingreso_6_meses.numeric' => 'El promedio de ingresos en 6 meses debe ser un número.',
            'created_by.exists' => 'El usuario creador no existe.',
            'updated_by.exists' => 'El usuario de actualización no existe.',
            'deleted_by.exists' => 'El usuario de eliminación no existe.',
        ];
    }
}
