<?php

namespace App\Http\Resources\CampoProducto;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampoProductoResource extends JsonResource{
    public function toArray(Request $request): array{
        return [
            'id'     => $this->campo->id,
            'nombre' => $this->campo->nombre,
            'slug'   => $this->campo->slug,
            'autocompletar_api' =>$this->campo->autocompletar_api,
            'tipo'   => $this->campo->tipo,
            'grupo'  => $this->grupo,
            'orden'  => $this->orden,
        ];
    }
}
