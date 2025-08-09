<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonaArchivo extends Model{
    use SoftDeletes;
    protected $table = 'persona_archivos';
    protected $fillable = [
        'persona_id',
        'nombre',
        'descripcion',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function persona(){
        return $this->belongsTo(Persona::class);
    }
    public function getUrlAttribute(){
        return asset("uploads/personas/{$this->persona_id}/{$this->nombre}");
    }
}
