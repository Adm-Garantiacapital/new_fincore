<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallePersona extends Model{
    use SoftDeletes;
    protected $table = 'detalle_persona';
    protected $fillable = [
        'persona_id',
        'anio',
        'nombre_comercial',
        'fecha_inscripcion',
        'fecha_inicio_actividades',
        'estado_contribuyente',
        'condicion_contribuyente',
        'domicilio_fiscal',
        'actividad_comercio_exterior',
        'actividad_economica',
        'ingresos_netos',
        'otros_ingresos',
        'total_activos',
        'total_cuentas_por_pagar',
        'total_pasivo',
        'total_patrimonio',
        'capital_social',
        'resultado_bruto',
        'resultado_antes_imp',
        'importe_pagado',
        'ingreso_12_meses',
        'ingreso_6_meses',
        'promedio_ingreso_12_meses',
        'promedio_ingreso_6_meses',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function persona(){
        return $this->belongsTo(Persona::class);
    }
}
