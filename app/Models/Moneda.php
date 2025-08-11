<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Moneda extends Model{
    use SoftDeletes;
    protected $table = 'monedas';
    protected $fillable = [
        'codigo',
        'nombre',
        'simbolo',
        'activo',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function condicionesFinancieras(){
        return $this->hasMany(PersonaCondicionFinanciera::class, 'moneda_id');
    }
}
