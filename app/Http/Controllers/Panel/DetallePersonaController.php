<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Detalle\DetallePersonaRequests;
use App\Http\Resources\DetallePersona\DetallePersonaResource;
use App\Models\DetallePersona;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Throwable;

class DetallePersonaController extends Controller{
    public function store(DetallePersonaRequests $request){
        try {
            Gate::authorize('create', DetallePersona::class);
            $data = $request->validated();
            $data['created_by'] = Auth::id();
            $detallePersona = DetallePersona::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Detalle de persona creado correctamente.',
                'id'      => $detallePersona->id,
                'data'    => new DetallePersonaResource($detallePersona),
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para crear un detalle de persona.'
            ], 403);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el detalle de persona.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
