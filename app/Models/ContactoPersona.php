<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactoPersona extends Model{
    protected $table = 'contactos_persona';
    protected $fillable = [
        'persona_id',
        'dni',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'cargo',
        'correo',
        'telefono',
        'pagina_web',
        'observacion',
    ];
    public function persona(){
        return $this->belongsTo(Persona::class);
    }
}
