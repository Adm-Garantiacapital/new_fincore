<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoCampo extends Model{
    protected $table = 'producto_campos';
    protected $fillable = [
        'producto_id',
        'tipo_documento_id',
        'campo_id',
        'grupo',
        'orden',
    ];
    public function producto(){
        return $this->belongsTo(Producto::class);
    }
    public function tipoDocumento(){
        return $this->belongsTo(TipoDocumento::class);
    }
    public function campo(){
        return $this->belongsTo(Campo::class);
    }
}
