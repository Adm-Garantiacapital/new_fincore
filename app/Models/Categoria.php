<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model{
    use HasFactory;
    protected $table = 'categorias';
    protected $fillable = ['nombre','estado'];
    public function productos(){
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}
