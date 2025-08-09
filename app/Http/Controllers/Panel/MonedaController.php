<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\Moneda\MonedaResources;
use App\Models\Moneda;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class MonedaController extends Controller{
    public function index(){
        try {
            Gate::authorize('viewAny', Moneda::class);
            $estados = Moneda::all();
            return response()->json([
                'total' => $estados->count(),
                'data' => MonedaResources::collection($estados),
            ]);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver los estados.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al listar estados.'], 500);
        }
    }
}
