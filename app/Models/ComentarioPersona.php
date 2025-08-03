<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComentarioPersona extends Model{
    use SoftDeletes;
    protected $table = 'comentario_persona';
    protected $fillable = ['persona_id', 'user_id', 'comentario', 'updated_by', 'deleted_by'];
    public function persona(){
        return $this->belongsTo(Persona::class);
    }
    public function autor(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
