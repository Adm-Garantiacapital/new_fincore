<?php

namespace App\Http\Resources\Moneda;

use Illuminate\Http\Resources\Json\JsonResource;

class MonedaResources extends JsonResource{
    public function toArray($request){
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
        ];
    }
}
