<?php

use App\Http\Controllers\Api\ConsultasDni;
use App\Http\Controllers\Api\SunatController;
use App\Http\Controllers\Api\SunatReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('consultas')->group(function () {
    Route::get('/ruc/{ruc?}', [SunatController::class, 'consultar'])->name('consultar.ruc');
    Route::get('/consultar-dni/{dni?}', [ConsultasDni::class, 'consultar'])->name('consultar.dni');
});

