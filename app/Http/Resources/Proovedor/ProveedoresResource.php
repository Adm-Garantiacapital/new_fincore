<?php

namespace App\Http\Resources\Proveedor;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProveedoresResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'ruc'                    => $this->ruc,
            'pagina_web'            => $this->pagina_web,
            'razon_social'          => $this->razon_social,
            'nombre_comercial'      => $this->nombre_comercial,
            'fecha_inicio_actividad' => Carbon::parse($this->fecha_inicio_actividad)->format('d-m-Y'),
            'actividad_economica'   => $this->actividad_economica,
            'direccion'             => $this->direccion,
            'ventas_aproximadas'    => $this->ventas_aproximadas,
        ];
    }
}
