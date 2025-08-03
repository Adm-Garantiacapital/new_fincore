<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubAreas\StoreSubAreasRequests;
use App\Http\Requests\SubAreas\UpdateSubAreasRequests;
use App\Http\Resources\SubAreas\SubAreasResources;
use App\Models\SubArea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class SubAreasController extends Controller{
    public function index(){
        try {
            Gate::authorize('viewAny', SubArea::class);
            $subareas = SubArea::all();
            return response()->json([
                'total' => $subareas->count(),
                'data' => SubAreasResources::collection($subareas),
            ]);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver las subáreas.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al listar las subáreas.'], 500);
        }
    }
    public function store(StoreSubAreasRequests $request){
        try {
            Gate::authorize('create', SubArea::class);
            $data = $request->validated();
            $data['created_by'] = Auth::id();
            $subarea = SubArea::create($data);
            return response()->json([
                'message' => 'Subárea creada correctamente.',
                'data' => new SubAreasResources($subarea)
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para crear una subárea.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al crear la subárea.'], 500);
        }
    }
    public function show($id){
        try {
            $subarea = SubArea::findOrFail($id);
            Gate::authorize('view', $subarea);
            return new SubAreasResources($subarea);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Subárea no encontrada.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver esta subárea.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al mostrar la subárea.'], 500);
        }
    }
    public function update(UpdateSubAreasRequests $request, $id){
        try {
            $subarea = SubArea::findOrFail($id);
            Gate::authorize('update', $subarea);
            $data = $request->validated();
            $data['updated_by'] = Auth::id();
            $subarea->update($data);
            return response()->json([
                'message' => 'Subárea actualizada correctamente.',
                'data' => new SubAreasResources($subarea)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Subárea no encontrada.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para editar esta subárea.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al actualizar la subárea.'], 500);
        }
    }
    public function delete($id){
        try {
            $subarea = SubArea::findOrFail($id);
            Gate::authorize('delete', $subarea);
            $subarea->deleted_by = Auth::id();
            $subarea->save();
            $subarea->delete();
            return response()->json(['message' => 'Subárea eliminada correctamente.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Subárea no encontrada.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta subárea.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al eliminar la subárea.'], 500);
        }
    }
}
