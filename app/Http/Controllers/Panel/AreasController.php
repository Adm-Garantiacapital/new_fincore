<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Areas\StoreAreasRequests;
use App\Http\Requests\Areas\UpdateAreasRequests;
use App\Http\Resources\Areas\AreasResources;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class AreasController extends Controller{
    public function index(){
        try {
            Gate::authorize('viewAny', Area::class);
            $areas = Area::all();
            return response()->json([
                'total' => $areas->count(),
                'data' => AreasResources::collection($areas),
            ]);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver las áreas.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al listar las áreas.'], 500);
        }
    }
    public function store(StoreAreasRequests $request){
        try {
            Gate::authorize('create', Area::class);
            $data = $request->validated();
            $data['created_by'] = Auth::id();
            $area = Area::create($data);
            return response()->json([
                'message' => 'Área creada correctamente.',
                'data' => new AreasResources($area)
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para crear un área.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al crear el área.'], 500);
        }
    }
    public function show($id){
        try {
            $area = Area::findOrFail($id);
            Gate::authorize('view', $area);
            return new AreasResources($area);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Área no encontrada.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver esta área.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al mostrar el área.'], 500);
        }
    }
    public function update(UpdateAreasRequests $request, $id){
        try {
            $area = Area::findOrFail($id);
            Gate::authorize('update', $area);
            $data = $request->validated();
            $data['updated_by'] = Auth::id();
            $area->update($data);
            return response()->json([
                'message' => 'Área actualizada correctamente.',
                'data' => new AreasResources($area)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Área no encontrada.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para editar esta área.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al actualizar el área.'], 500);
        }
    }
    public function delete($id){
        try {
            $area = Area::findOrFail($id);
            Gate::authorize('delete', $area);
            $area->deleted_by = Auth::id();
            $area->save();
            $area->delete();
            return response()->json(['message' => 'Área eliminada correctamente.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Área no encontrada.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta área.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al eliminar el área.'], 500);
        }
    }
}
