<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model{
    use SoftDeletes;
    protected $fillable = ['nombre', 'descripcion', 'estado_id', 'created_by', 'updated_by', 'deleted_by'];
    public function estado(){
        return $this->belongsTo(Estado::class);
    }
    public function persona(){
        return $this->hasOne(Persona::class);
    }
}
