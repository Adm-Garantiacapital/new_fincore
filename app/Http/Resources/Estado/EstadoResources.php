<?php

namespace App\Http\Resources\Estado;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EstadoResources extends JsonResource{
    public function toArray($request){
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion??'Sin descripcion',
            'creacion' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s A'),
            'actualizacion' => Carbon::parse($this->updated_at)->format('d-m-Y H:i:s A'),
        ];
    }
}
