<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampoProducto\CampoProductoResource;
use App\Http\Resources\TipoDocumento\TipoDocumentoResource;
use App\Models\ProductoCampo;
use App\Models\TipoDocumento;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class TipoDocumentoController extends Controller{
    public function index(){
        try {
            Gate::authorize('viewAny', TipoDocumento::class);
            $tiposDocumento = TipoDocumento::all();
            return response()->json([
                'total' => $tiposDocumento->count(),
                'data' => TipoDocumentoResource::collection($tiposDocumento),
            ]);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver los tipos de documento.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al listar los tipos de documento.'], 500);
        }
    }
    public function tiposDocumentoPorProducto($productoId){
        try {
            Gate::authorize('ver tipo documento');
            $tiposDocumentoIds = ProductoCampo::where('producto_id', $productoId)
                ->pluck('tipo_documento_id')
                ->unique();
            $tiposDocumento = TipoDocumento::whereIn('id', $tiposDocumentoIds)->get();
            return response()->json([
                'data' => TipoDocumentoResource::collection($tiposDocumento),
            ]);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver tipos de documento.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al obtener tipos de documento.'], 500);
        }
    }
    public function camposPorProductoYTipo($productoId, $tipoDocumentoId)
{
    try {
        Gate::authorize('ver campos productos');

        $campos = ProductoCampo::with('campo')
            ->where('producto_id', $productoId)
            ->where('tipo_documento_id', $tipoDocumentoId)
            ->orderBy('grupo')
            ->orderBy('orden')
            ->get()
            ->groupBy('grupo');

        $response = $campos->map(function ($grupoCampos, $grupo) {
            return [
                'grupo'  => $grupo ?? 'General', // Si grupo es null
                'campos' => CampoProductoResource::collection($grupoCampos),
            ];
        })->values(); // <- remove keys

        return response()->json([
            'success' => true,
            'data' => $response,
        ]);

    } catch (AuthorizationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes permiso para ver campos de productos.',
        ], 403);
    } catch (Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener campos.',
            'error' => config('app.debug') ? $e->getMessage() : null,
        ], 500);
    }
}

}
