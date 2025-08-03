<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Estado;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
class EstadosWeb extends Controller{
    public function views(): Response{
        Gate::authorize('viewAny', Estado::class);
        return Inertia::render('panel/Estados/indexEstado');
    }
}
