<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\SubArea;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
class SubAreasWeb extends Controller{
    public function views(): Response{
        Gate::authorize('viewAny', SubArea::class);
        return Inertia::render('panel/SubArea/indexSubArea');
    }
}
