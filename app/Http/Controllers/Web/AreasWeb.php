<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
class AreasWeb extends Controller{
    public function views(): Response{
        Gate::authorize('viewAny', Area::class);
        return Inertia::render('panel/Areas/indexAreas');
    }
}
