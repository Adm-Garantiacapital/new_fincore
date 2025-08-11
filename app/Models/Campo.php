<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campo extends Model{
    protected $fillable = ['nombre', 'slug', 'tipo', 'autocompletar_api'];
    public function productoCampos(){
        return $this->hasMany(ProductoCampo::class);
    }
}
