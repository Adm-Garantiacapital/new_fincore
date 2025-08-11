<?php

namespace App\Http\Resources\DetallePersona;

use App\Http\Resources\Carnet\CarnetResource;
use App\Http\Resources\Cliente\ClienteResource;
use App\Http\Resources\Proovedor\ProveedoresResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetallePersonaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'producto'  => $this->producto?->nombre,
            'estado'    => $this->estado?->nombre,
            'cliente'   => $this->cliente ? new ClienteResource($this->cliente) : null,
            'proveedor' => $this->proveedor ? new ProveedoresResource($this->proveedor) : null,
            'carnet'    => $this->carnet ? new CarnetResource($this->carnet) : null,
        ];
    }
}
