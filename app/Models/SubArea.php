<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubArea extends Model{
    use SoftDeletes;
    protected $fillable = ['nombre', 'codigo', 'area_id', 'estado_id', 'created_by', 'updated_by', 'deleted_by'];
    public function area(){
        return $this->belongsTo(Area::class);
    }
    public function estado(){
        return $this->belongsTo(Estado::class);
    }
    public function productos(){
        return $this->hasMany(Producto::class);
    }
}
