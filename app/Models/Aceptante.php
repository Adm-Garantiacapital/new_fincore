<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aceptante extends Model{
    use SoftDeletes;
    protected $table = 'aceptantes';
    protected $fillable = [
        'persona_id',
        'ruc',
        'razon_social',
        'fecha_inicio',
        'fecha_sentinel',
        'cliente_situacion',
        'endeud_bancario',
        'prorestos',
        'protestos',
        'rl_nombre',
        'situacion_sf',
        'edad',
        'top',
        'ventas_aprox_pagina_web',
        'entidades_apefact',
        'endeud_apfec',
        'endeud_prom',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_sentinel' => 'date',
        'prorestos' => 'boolean',
        'endeud_bancario' => 'decimal:2',
        'ventas_aprox_pagina_web' => 'decimal:2',
        'endeud_apfec' => 'decimal:2',
        'endeud_prom' => 'decimal:2',
    ];
    public function persona(){
        return $this->belongsTo(Persona::class);
    }
    public function creadoPor(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function actualizadoPor(){
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function eliminadoPor(){
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
