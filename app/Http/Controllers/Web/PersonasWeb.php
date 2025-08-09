<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
class PersonasWeb extends Controller{
    public function views(): Response{
        Gate::authorize('viewAny', Persona::class);
        return Inertia::render('panel/Persona/indexPersona');
    }
    public function viewsList(): Response{
        Gate::authorize('viewAny', Persona::class);
        return Inertia::render('panel/Persona/ListPersona');
    }
}
