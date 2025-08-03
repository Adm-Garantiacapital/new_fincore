<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model{
    use SoftDeletes;
    protected $fillable = ['name', 'descripcion', 'created_by', 'updated_by', 'deleted_by'];
    public function areas(){
        return $this->hasMany(Area::class);
    }
    public function subAreas(){
        return $this->hasMany(SubArea::class);
    }
    public function productos(){
        return $this->hasMany(Producto::class);
    }
    public function personas(){
        return $this->hasMany(Persona::class);
    }
}
