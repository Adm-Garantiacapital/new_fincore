<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Estado\StoreEstadoRequests;
use App\Http\Requests\Estado\UpdateEstadoRequests;
use App\Http\Resources\Estado\EstadoResources;
use App\Models\Estado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class EstadoController extends Controller{
    public function index(){
        try {
            Gate::authorize('viewAny', Estado::class);
            $estados = Estado::all();
            return response()->json([
                'total' => $estados->count(),
                'data' => EstadoResources::collection($estados),
            ]);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver los estados.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al listar estados.'], 500);
        }
    }
    public function store(StoreEstadoRequests $request){
        try {
            Gate::authorize('create', Estado::class);
            $data = $request->validated();
            $data['created_by'] = Auth::id();
            $estado = Estado::create($data);
            return response()->json([
                'message' => 'Estado creado correctamente.',
                'data' => new EstadoResources($estado)
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para crear un estado.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al crear el estado.'], 500);
        }
    }
    public function show($id){
        try {
            $estado = Estado::findOrFail($id);
            Gate::authorize('view', $estado);
            return new EstadoResources($estado);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Estado no encontrado.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver este estado.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al mostrar el estado.'], 500);
        }
    }
    public function update(UpdateEstadoRequests $request, $id){
        try {
            $estado = Estado::findOrFail($id);
            Gate::authorize('update', $estado);
            $data = $request->validated();
            $data['updated_by'] = Auth::id();
            $estado->update($data);
            return response()->json([
                'message' => 'Estado actualizado correctamente.',
                'data' => new EstadoResources($estado)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Estado no encontrado.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para editar este estado.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al actualizar el estado.'], 500);
        }
    }
    public function delete($id){
        try {
            $estado = Estado::findOrFail($id);
            Gate::authorize('delete', $estado);
            $estado->deleted_by = Auth::id();
            $estado->save();
            $estado->delete();
            return response()->json(['message' => 'Estado eliminado correctamente.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Estado no encontrado.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para eliminar este estado.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al eliminar el estado.'], 500);
        }
    }
}
