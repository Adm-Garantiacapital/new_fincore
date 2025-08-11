<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonaCondicionFinanciera extends Model{
    use SoftDeletes;
    protected $table = 'persona_condiciones_financieras';
    protected $fillable = [
        'persona_id',
        'linea_cliente',
        'porcentaje_anticipo',
        'monto_comision',
        'tasa_tem',
        'adelanto',
        'linea_adelanto',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function persona(){
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}
