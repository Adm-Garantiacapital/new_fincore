<?php

namespace App\Http\Resources\Persona;

use App\Http\Resources\Cliente\ClienteResource;
use App\Http\Resources\Proveedor\ProveedoresResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonaResource extends JsonResource{
    public function toArray(Request $request): array{
        return [
            'id' => $this->id,
            'producto_id' => $this->producto_id,
            'tipo_documento_id' => $this->tipo_documento_id,
            'estado' => $this->estado->nombre,
            'cliente' => $this->cliente ? new ClienteResource($this->cliente) : null,
            'proveedor' => $this->proveedor ? new ProveedoresResource($this->proveedor) : null,
            'creacion' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s A'),
            'actualizacion' => Carbon::parse($this->updated_at)->format('d-m-Y H:i:s A'),
        ];
    }
}
