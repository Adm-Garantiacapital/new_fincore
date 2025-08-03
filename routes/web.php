<?php

use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UsuariosController;
use App\Http\Controllers\Web\DetallePersonWeb;
use App\Http\Controllers\Web\EstadosWeb;
use App\Http\Controllers\Web\PersonasWeb;
use App\Http\Controllers\Web\ProductosWeb;
use App\Http\Controllers\Web\SubAreasWeb;
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
        Route::get('/area', [UserWeb::class,'views'])->name('area.views');
        Route::get('/detalle', [DetallePersonWeb::class,'views'])->name('detalle.views');
        Route::get('/estado', [EstadosWeb::class,'views'])->name('estado.views');
        Route::get('/tipo', [PersonasWeb::class,'views'])->name('tipo.views');
        Route::get('/producto', [ProductosWeb::class,'views'])->name('producto.views');
        Route::get('/sub/areas', [SubAreasWeb::class,'views'])->name('sub.views');
        Route::get('/usuario', [UserWeb::class,'index'])->name('usuario.index');
        Route::get('/roles', [UserWeb::class, 'roles'])->name('roles.view');
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
