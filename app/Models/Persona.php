<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'tipo',
        'documento',
        'tipo_documento',
        'email',
        'telefono',
        'direccion',
        'razon_social',
        'nombre_comercial',
        'fecha_inicio_actividad',
        'actividad_economica',
        'ventas_aproximadas',
        'pagina_web',
        'fecha_nacimiento',
        'sexo',
        'estado_civil',
        'producto_id',
        'estado_id',
        'descripcion',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function producto(){
        return $this->belongsTo(Producto::class);
    }
    public function estado(){
        return $this->belongsTo(Estado::class);
    }
    public function detalle(){
        return $this->hasOne(DetallePersona::class);
    }
    public function comentarios(){
        return $this->hasMany(ComentarioPersona::class);
    }
}
