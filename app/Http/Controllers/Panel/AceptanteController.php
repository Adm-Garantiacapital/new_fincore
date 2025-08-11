<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Aceptante\AceptanteRequests;
use App\Http\Resources\Aceptante\aceptanteResource;
use App\Models\Aceptante;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Throwable;

class AceptanteController extends Controller{
    public function store(AceptanteRequests $request){
        try {
            Gate::authorize('create', Aceptante::class);
            $data = $request->validated();
            $data['created_by'] = Auth::id();
            $area = Aceptante::create($data);
            return response()->json([
                'message' => 'Área creada correctamente.',
                'data' => new aceptanteResource($area)
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para crear un área.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al crear el área.'], 500);
        }
    }
}
