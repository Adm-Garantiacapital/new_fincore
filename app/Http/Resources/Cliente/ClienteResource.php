<?php

namespace App\Http\Resources\Cliente;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource{
    public function toArray(Request $request): array{
        return [
            'id'                 => $this->id,
            'dni'                => $this->dni,
            'nombre'             => $this->nombre,
            'apellidos'   => $this->apellido_paterno . ' ' . $this->apellido_materno,
            'fecha_nacimiento' => Carbon::parse($this->fecha_nacimiento)->format('d-m-Y'),
            'telefono'           => $this->telefono,
            'direccion'          => $this->direccion,
        ];
    }
}
