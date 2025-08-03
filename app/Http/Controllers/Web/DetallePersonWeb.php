<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\DetallePersona;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
class DetallePersonWeb extends Controller{
    public function views(): Response{
        Gate::authorize('viewAny', DetallePersona::class);
        return Inertia::render('panel/DetallePersona/indexDetallePersona');
    }
}
