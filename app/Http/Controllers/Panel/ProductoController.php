<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Producto\StoreProductoRequests;
use App\Http\Requests\Producto\UpdateProductoRequests;
use App\Http\Resources\Producto\ProductoResource;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class ProductoController extends Controller{
    public function index(){
        try {
            Gate::authorize('viewAny', Producto::class);
            $productos = Producto::all();
            return response()->json([
                'total' => $productos->count(),
                'data' => ProductoResource::collection($productos),
            ]);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver los productos.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al listar los productos.'], 500);
        }
    }
    public function store(StoreProductoRequests $request){
        try {
            Gate::authorize('create', Producto::class);
            $data = $request->validated();
            $data['created_by'] = Auth::id();
            $producto = Producto::create($data);
            return response()->json([
                'message' => 'Producto creado correctamente.',
                'data' => new ProductoResource($producto)
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para crear un producto.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al crear el producto.'], 500);
        }
    }
    public function show($id){
        try {
            $producto = Producto::findOrFail($id);
            Gate::authorize('view', $producto);
            return new ProductoResource($producto);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Producto no encontrado.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver este producto.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al mostrar el producto.'], 500);
        }
    }
    public function update(UpdateProductoRequests $request, $id){
        try {
            $producto = Producto::findOrFail($id);
            Gate::authorize('update', $producto);
            $data = $request->validated();
            $data['updated_by'] = Auth::id();
            $producto->update($data);
            return response()->json([
                'message' => 'Producto actualizado correctamente.',
                'data' => new ProductoResource($producto)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Producto no encontrado.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para editar este producto.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al actualizar el producto.'], 500);
        }
    }
    public function destroy($id){
        try {
            $producto = Producto::findOrFail($id);
            Gate::authorize('delete', $producto);
            $producto->deleted_by = Auth::id();
            $producto->save();
            $producto->delete();
            return response()->json(['message' => 'Producto eliminado correctamente.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Producto no encontrado.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para eliminar este producto.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al eliminar el producto.'], 500);
        }
    }
}
