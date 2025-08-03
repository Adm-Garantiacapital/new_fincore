<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model{
    use SoftDeletes;
    protected $fillable = ['name', 'estado_id', 'created_by', 'updated_by', 'deleted_by'];
    public function estado(){
        return $this->belongsTo(Estado::class);
    }
    public function subAreas(){
        return $this->hasMany(SubArea::class);
    }
}
