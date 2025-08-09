<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\StoreClienteRequests;
use App\Http\Requests\CondicionFinanciera\StoreCondicionFinancieraRequests;
use App\Http\Requests\Persona\StorePersonaRequests;
use App\Http\Requests\PersonaArchivo\StorePersonaArchivo;
use App\Http\Resources\Persona\PersonaResource;
use App\Models\Cliente;
use App\Models\Persona;
use App\Models\PersonaArchivo;
use App\Models\PersonaCondicionFinanciera;
use App\QueryFilters\PersonaSearchFilter;
use App\QueryFilters\ProductoFilter;
use App\QueryFilters\TipoDocumentoFilter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Pipeline\Pipeline;

class PersonaController extends Controller{
    public function index(Request $request){
        try {
            Gate::authorize('viewAny', Persona::class);
            $perPage = $request->query('per_page', 15);
            $personas = app(Pipeline::class)
                ->send(Persona::with(['cliente', 'proveedor']))
                ->through([
                    ProductoFilter::class,
                    TipoDocumentoFilter::class,
                    PersonaSearchFilter::class,
                ])
                ->thenReturn()
                ->paginate($perPage);
            return response()->json([
                'data' => PersonaResource::collection($personas->items()),
                'pagination' => [
                    'current_page' => $personas->currentPage(),
                    'per_page' => $personas->perPage(),
                    'total' => $personas->total(),
                    'last_page' => $personas->lastPage(),
                    'from' => $personas->firstItem(),
                    'to' => $personas->lastItem(),
                    'has_more_pages' => $personas->hasMorePages(),
                    'next_page_url' => $personas->nextPageUrl(),
                    'prev_page_url' => $personas->previousPageUrl(),
                ]
            ]);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No tienes permiso para ver las personas.'], 403);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error al listar las personas.'], 500);
        }
    }
    public function Store(
        StorePersonaRequests $personaRequest,
        StoreClienteRequests $clienteRequest,
        StoreCondicionFinancieraRequests $condicionFinancieraRequest,
        StorePersonaArchivo $archivoRequest
    ) {
        Gate::authorize('create', Cliente::class);
        DB::beginTransaction();
        try {
            #1. Crear Persona
            $personaData = $personaRequest->validated();
            $personaData['created_by'] = Auth::id();
            $personaData['estado_id'] = 3;
            $persona = Persona::create($personaData);
            #2. Crear Cliente
            $clienteData = $clienteRequest->validated();
            $clienteData['persona_id'] = $persona->id;
            $clienteData['created_by'] = Auth::id();
            $cliente = Cliente::create($clienteData);
            #3. Crear Condición Financiera
            $condicionFinancieraData = $condicionFinancieraRequest->validated();
            $condicionFinancieraData['persona_id'] = $persona->id;
            $condicionFinancieraData['created_by'] = Auth::id();
            $condicionFinanciera = PersonaCondicionFinanciera::create($condicionFinancieraData);
            if ($archivoRequest->hasFile('archivos')) {
                $rutaCarpeta = public_path("uploads/personas/{$persona->id}");
                if (!file_exists($rutaCarpeta)) {
                    mkdir($rutaCarpeta, 0777, true);
                }
                foreach ($archivoRequest->file('archivos') as $archivo) {
                    $nombre = time() . '_' . $archivo->getClientOriginalName();
                    $archivo->move($rutaCarpeta, $nombre);
                    PersonaArchivo::create([
                        'persona_id' => $persona->id,
                        'nombre' => $nombre,
                        'descripcion' => $archivoRequest->input('descripcion'),
                        'created_by' => Auth::id(),
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Cliente, persona, condición financiera y archivos registrados correctamente.',
                'data' => [
                    'persona' => $persona,
                    'cliente' => $cliente,
                    'condicion_financiera' => $condicionFinanciera,
                ],
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error al registrar cliente/persona/condición financiera/archivos: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'persona_data' => $personaRequest->all(),
                'cliente_data' => $clienteRequest->all(),
                'condicion_financiera_data' => $condicionFinancieraRequest->all(),
                'archivos_data' => $archivoRequest->all(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el cliente, persona, condición financiera o archivos.',
            ], 500);
        }
    }
}
