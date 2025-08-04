<?php

namespace App\Http\Resources\Producto;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource{
    public function toArray($request){
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion??'Sin descripcion',
            'estado' => $this->estado->nombre,
            'creacion' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s A'),
            'actualizacion' => Carbon::parse($this->updated_at)->format('d-m-Y H:i:s A'),
        ];
    }
}
