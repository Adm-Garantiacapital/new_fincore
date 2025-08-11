<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model{
    use SoftDeletes;
    protected $table = 'proveedores';
    protected $fillable = [
        'ruc',
        'razon_social',
        'nombre_comercial',
        'fecha_inicio_actividad',
        'actividad_economica',
        'direccion',
        'ventas_aproximadas',
        'persona_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function persona(){
        return $this->belongsTo(Persona::class);
    }
    public function creador(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function editor(){
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function eliminador(){
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
