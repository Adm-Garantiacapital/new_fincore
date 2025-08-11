<?php

namespace App\Http\Resources\Carnet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarnetResource extends JsonResource{
    public function toArray(Request $request): array{
        return [
            'id' => $this->id,
            'documento' => $this->documento,
            'nombre' => $this->nombre,
            'apellido_paterno' => $this->apellido_paterno.' '.$this->apellido_materno
        ];
    }
}
