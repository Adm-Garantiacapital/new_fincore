<?php

namespace App\Http\Resources\SubAreas;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SubAreasResources extends JsonResource{
    public function toArray($request){
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'estado' => $this->estado->nombre,
            'creacion' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s A'),
            'actualizacion' => Carbon::parse($this->updated_at)->format('d-m-Y H:i:s A'),
        ];
    }
}
