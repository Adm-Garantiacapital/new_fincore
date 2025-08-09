<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model{
    use SoftDeletes;
    protected $fillable = [
        'tipo_documento_id',
        'producto_id',
        'estado_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function tipoDocumento(){
        return $this->belongsTo(TipoDocumento::class);
    }
    public function producto(){
        return $this->belongsTo(Producto::class);
    }
    public function estado(){
        return $this->belongsTo(Estado::class);
    }
    public function cliente(){
        return $this->hasOne(Cliente::class);
    }
    public function proveedor(){
        return $this->hasOne(Proveedor::class);
    }
    public function contactos(){
        return $this->hasMany(ContactoPersona::class);
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
    public function condicionesFinancieras(){
        return $this->hasMany(PersonaCondicionFinanciera::class, 'persona_id');
    }
}
