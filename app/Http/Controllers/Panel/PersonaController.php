<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarnetExtrangeria\StoreCarnetExtrangeriaRequests;
use App\Http\Requests\Cliente\StoreClienteRequests;
use App\Http\Requests\CondicionFinanciera\StoreCondicionFinancieraRequests;
use App\Http\Requests\Persona\StoreContactoPersonaRequests;
use App\Http\Requests\Persona\StorePersonaCondicionFinancieraRequests;
use App\Http\Requests\Persona\StorePersonaRequests;
use App\Http\Requests\PersonaArchivo\StorePersonaArchivo;
use App\Http\Requests\Proveedor\StoreProveedorRequests;
use App\Http\Resources\Persona\PersonaResource;
use App\Models\CarnetDeExtranjeria;
use App\Models\Cliente;
use App\Models\ContactoPersona;
use App\Models\Persona;
use App\Models\PersonaArchivo;
use App\Models\PersonaCondicionFinanciera;
use App\Models\Proveedor;
use App\QueryFilters\PersonaSearchFilter;
use App\QueryFilters\ProductoFilter;
use App\QueryFilters\TipoDocumentoFilter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
                'id' => $persona->id,
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
    public function StoreRuc(
        StorePersonaRequests $personaRequest,
        StoreProveedorRequests $proveedorRequest,
        StorePersonaCondicionFinancieraRequests $condicionFinancieraRequest,
        StorePersonaArchivo $archivoRequest,
        StoreContactoPersonaRequests $contactoRequest
    ) {
        Gate::authorize('create', Proveedor::class);
        DB::beginTransaction();
        
        try {
            // 1. Crear Persona
            $personaData = $personaRequest->validated();
            $personaData['created_by'] = Auth::id();
            $personaData['estado_id'] = $personaData['estado_id'] ?? 3;
            $persona = Persona::create($personaData);

            // 2. Crear Proveedor
            $proveedorData = $proveedorRequest->validated();
            $proveedorData['persona_id'] = $persona->id;
            $proveedorData['created_by'] = Auth::id();
            $proveedor = Proveedor::create($proveedorData);

            // 3. Crear Condición Financiera
            $condicionFinancieraData = $condicionFinancieraRequest->validated();
            $condicionFinancieraData['persona_id'] = $persona->id;
            $condicionFinancieraData['created_by'] = Auth::id();
            $condicionFinanciera = PersonaCondicionFinanciera::create($condicionFinancieraData);

            // 4. Procesar archivos si existen
            if ($archivoRequest->hasFile('archivos')) {
                $rutaCarpeta = public_path("uploads/ruc/{$persona->id}");
                if (!file_exists($rutaCarpeta)) {
                    mkdir($rutaCarpeta, 0777, true);
                }

                foreach ($archivoRequest->file('archivos') as $archivo) {
                    $nombre = time() . '_' . $archivo->getClientOriginalName();
                    $archivo->move($rutaCarpeta, $nombre);
                    
                    PersonaArchivo::create([
                        'persona_id' => $persona->id,
                        'nombre' => $nombre,
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            // 5. Crear ContactoPersona si se proporcionan datos
            $contactoPersona = null;
            if ($contactoRequest->hasAny(['dni', 'nombre'])) {
                $contactoData = $contactoRequest->validated();
                $contactoData['persona_id'] = $persona->id;
                $contactoPersona = ContactoPersona::create($contactoData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proveedor, persona, condición financiera y archivos registrados correctamente.',
                'id' => $persona->id,
                'data' => [
                    'persona' => $persona,
                    'proveedor' => $proveedor,
                    'condicion_financiera' => $condicionFinanciera,
                    'contacto' => $contactoPersona,
                ],
            ], 201);

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Error al registrar proveedor/persona/condición financiera/archivos: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'persona_data' => $personaRequest->all(),
                'proveedor_data' => $proveedorRequest->all(),
                'condicion_financiera_data' => $condicionFinancieraRequest->all(),
                'archivos_data' => $archivoRequest->all(),
                'contacto_data' => $contactoRequest->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el proveedor, persona, condición financiera o archivos.',
            ], 500);
        }
    }
    public function StoreCarnetExtranjeria(
        StorePersonaRequests $personaRequest,
        StoreCarnetExtrangeriaRequests $carnetRequest,
        StorePersonaCondicionFinancieraRequests $condicionFinancieraRequest,
        StorePersonaArchivo $archivoRequest
    ) {
        // Autorización
        Gate::authorize('create', CarnetDeExtranjeria::class);

        DB::beginTransaction();
        
        try {
            // 1. Crear Persona
            $personaData = $personaRequest->validated();
            $personaData['created_by'] = Auth::id();
            $personaData['estado_id'] = $personaData['estado_id'] ?? 3;
            $persona = Persona::create($personaData);

            // 2. Crear Carnet de Extranjería
            $carnetData = $carnetRequest->validated();
            $carnetData['persona_id'] = $persona->id;
            $carnetData['created_by'] = Auth::id();
            
            // Procesar imagen/foto del carnet si existe
            if ($carnetRequest->hasFile('imagenes')) {
                $rutaFotos = public_path("uploads/carnets/{$persona->id}");
                if (!file_exists($rutaFotos)) {
                    mkdir($rutaFotos, 0777, true);
                }
                
                $foto = $carnetRequest->file('imagenes');
                $nombreFoto = Str::uuid()->toString() . '.' . $foto->getClientOriginalExtension();
                $foto->move($rutaFotos, $nombreFoto);
                
                $carnetData['imagenes'] = $nombreFoto;
            }
            
            $carnetExtranjeria = CarnetDeExtranjeria::create($carnetData);

            // 3. Crear Condición Financiera
            $condicionFinancieraData = $condicionFinancieraRequest->validated();
            $condicionFinancieraData['persona_id'] = $persona->id;
            $condicionFinancieraData['created_by'] = Auth::id();
            $condicionFinanciera = PersonaCondicionFinanciera::create($condicionFinancieraData);

            // 4. Procesar archivos adicionales si existen
            if ($archivoRequest->hasFile('archivos')) {
                $rutaCarpeta = public_path("uploads/personas/{$persona->id}");
                if (!file_exists($rutaCarpeta)) {
                    mkdir($rutaCarpeta, 0777, true);
                }

                foreach ($archivoRequest->file('archivos') as $archivo) {
                    $nombre = Str::uuid()->toString() . '.' . $archivo->getClientOriginalExtension();
                    $archivo->move($rutaCarpeta, $nombre);
                    
                    PersonaArchivo::create([
                        'persona_id' => $persona->id,
                        'nombre' => $nombre,
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Carnet de extranjería, persona, condición financiera y archivos registrados correctamente.',
                'id' => $persona->id,
                'data' => [
                    'persona' => $persona,
                    'carnet_extranjeria' => $carnetExtranjeria,
                    'condicion_financiera' => $condicionFinanciera,
                ],
            ], 201);

        } catch (Throwable $e) {
            DB::rollBack();
            
            Log::error('Error al registrar carnet de extranjería/persona/condición financiera/archivos: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'persona_data' => $personaRequest->all(),
                'carnet_data' => $carnetRequest->all(),
                'condicion_financiera_data' => $condicionFinancieraRequest->all(),
                'archivos_data' => $archivoRequest->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el carnet de extranjería, persona, condición financiera o archivos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
