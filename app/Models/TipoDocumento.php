<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model {
    protected $table = 'tipos_documento';
    protected $fillable = ['nombre'];
    public function productoCampos() {
        return $this->hasMany(ProductoCampo::class);
    }
    public function personas() {
        return $this->hasMany(Persona::class);
    }
}
