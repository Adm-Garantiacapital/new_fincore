<?php

namespace App\Http\Resources\TipoDocumento;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoDocumentoResource extends JsonResource{
    public function toArray($request){
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
        ];
    }
}
