<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model{
    use SoftDeletes;
    protected $fillable = ['nombre', 'descripcion', 'sub_area_id', 'estado_id', 'created_by', 'updated_by', 'deleted_by'];
    public function subArea(){
        return $this->belongsTo(SubArea::class);
    }
    public function estado(){
        return $this->belongsTo(Estado::class);
    }
    public function persona(){
        return $this->hasOne(Persona::class);
    }
}
