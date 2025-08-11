<?php

namespace App\Http\Resources\Aceptante;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class aceptanteResource extends JsonResource{
    public function toArray($request){
        return [
            'id' => $this->id,
            'creacion' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s A'),
            'actualizacion' => Carbon::parse($this->updated_at)->format('d-m-Y H:i:s A'),
        ];
    }
}
