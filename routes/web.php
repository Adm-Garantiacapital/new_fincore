<?php

use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UsuariosController;
use App\Http\Controllers\Panel\AreasController;
use App\Http\Controllers\Panel\EstadoController;
use App\Http\Controllers\Panel\SubAreasController;
use App\Http\Controllers\Panel\ProductoController;
use App\Http\Controllers\Web\AreasWeb;
use App\Http\Controllers\Web\DetallePersonWeb;
use App\Http\Controllers\Web\EstadosWeb;
use App\Http\Controllers\Web\PersonasWeb;
use App\Http\Controllers\Web\ProductosWeb;
use App\Http\Controllers\Web\SubAreasWeb;
use App\Http\Controllers\Web\TipoWeb;
use App\Http\Controllers\Web\UserWeb;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    #PARA QUE CUANDO SE CREA UN USUARIO O MODIFICA SU PASSWORD LO REDIRECCIONE PARA QUE PUEDA ACTUALIZAR
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return Inertia::render('Dashboard', [
            'mustReset' => $user->restablecimiento == 0,
        ]);
    })->name('dashboard');
    #Frontend
    Route::prefix('panel')->group(function(){
        Route::get('/area', [AreasWeb::class,'views'])->name('area.views');
        Route::get('/detalle', [DetallePersonWeb::class,'views'])->name('detalle.views');
        Route::get('/estado', [EstadosWeb::class,'views'])->name('estado.views');
        Route::get('/tipo', [PersonasWeb::class,'views'])->name('tipo.views');
        Route::get('/producto', [ProductosWeb::class,'views'])->name('producto.views');
        Route::get('/sub/areas/{id}', [SubAreasWeb::class, 'views'])->name('sub.views');
        Route::get('/usuario', [UserWeb::class,'index'])->name('usuario.index');
        Route::get('/roles', [UserWeb::class, 'roles'])->name('roles.view');
    });
    #Estados -> BACKEND
    Route::prefix('estados')->group(function(){
        Route::get('/', [EstadoController::class, 'index'])->name('estados.index');
        Route::post('/', [EstadoController::class, 'store'])->name('estados.store');
        Route::get('/{id}', [EstadoController::class, 'show'])->name('estados.show');
        Route::put('/{id}', [EstadoController::class, 'update'])->name('estados.update');
        Route::delete('/{id}', [EstadoController::class, 'delete'])->name('estados.delete');
    });
    #Productos -> BACKEND
    Route::prefix('productos')->group(function(){
        Route::get('/', [ProductoController::class, 'index'])->name('estados.index');
        Route::post('/', [ProductoController::class, 'store'])->name('estados.store');
        Route::get('/{id}', [ProductoController::class, 'show'])->name('estados.show');
        Route::put('/{id}', [ProductoController::class, 'update'])->name('estados.update');
        Route::delete('/{id}', [ProductoController::class, 'delete'])->name('estados.delete');
    });
    #Areas -> BACKEND
    Route::prefix('areas')->group(function(){
        Route::get('/', [AreasController::class, 'index'])->name('areas.index');
        Route::post('/', [AreasController::class, 'store'])->name('areas.store');
        Route::get('/{id}', [AreasController::class, 'show'])->name('areas.show');
        Route::put('/{id}', [AreasController::class, 'update'])->name('areas.update');
        Route::delete('/{id}', [AreasController::class, 'delete'])->name('areas.delete');
    });
    #Sub Areas -> BACKEND
    Route::prefix('subareas')->group(function(){
        Route::get('/{area_id}/list', [SubAreasController::class, 'index'])->name('subareas.index');
        Route::post('/', [SubAreasController::class, 'store'])->name('subareas.store');
        Route::get('/{id}', [SubAreasController::class, 'show'])->name('subareas.show');
        Route::put('/{id}', [SubAreasController::class, 'update'])->name('subareas.update');
        Route::delete('/{id}', [SubAreasController::class, 'delete'])->name('subareas.delete');
    });
    #USUARIOS -> BACKEND
    Route::prefix('usuarios')->group(function(){
        Route::get('/', [UsuariosController::class, 'index'])->name('usuarios.index');
        Route::post('/',[UsuariosController::class, 'store'])->name('usuarios.store');
        Route::get('/{user}',[UsuariosController::class, 'show'])->name('usuarios.show');
        Route::put('/{user}',[UsuariosController::class, 'update'])->name('usuarios.update');
        Route::delete('/{user}',[UsuariosController::class, 'destroy'])->name('usuarios.destroy');
    });
    #ROLES => BACKEND
    Route::prefix('rol')->group(function () {
        Route::get('/', [RolesController::class, 'index'])->name('roles.index');
        Route::get('/Permisos', [RolesController::class, 'indexPermisos'])->name('roles.indexPermisos');
        Route::post('/', [RolesController::class, 'store'])->name('roles.store');
        Route::get('/{id}', [RolesController::class, 'show'])->name('roles.show');
        Route::put('/{id}', [RolesController::class, 'update'])->name('roles.update');
        Route::delete('/{id}', [RolesController::class, 'destroy'])->name('roles.destroy');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
