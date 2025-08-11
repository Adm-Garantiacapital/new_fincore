<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarnetDeExtranjeria extends Model{
    use SoftDeletes;
    protected $table = 'carnets_de_extranjeria';
    protected $fillable = [
        'documento',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'nacionalidad',
        'sexo',
        'estado_civil',
        'telefono',
        'imagenes',
        'pagina_web',
        'direccion',
        'persona_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $dates = [
        'fecha_nacimiento',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function persona(){
        return $this->belongsTo(Persona::class);
    }
    public function creador(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function actualizador(){
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function eliminador(){
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
