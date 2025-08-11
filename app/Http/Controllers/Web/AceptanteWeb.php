<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Http\Resources\DetallePersona\DetallePersonaResource;
use App\Models\Aceptante;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
class AceptanteWeb extends Controller{
    public function views($id): Response{
        Gate::authorize('viewAny', Aceptante::class);
        $persona = Aceptante::with(['producto', 'estado'])->findOrFail($id);
        $personaResource = new DetallePersonaResource($persona);
        return Inertia::render('panel/Aceptante/indexAceptante', [
            'detallePersona' => $personaResource,
        ]);
    }
}
