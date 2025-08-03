<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Areas\AreasResources;
use App\Models\Area;
use App\Models\SubArea;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class SubAreasWeb extends Controller{
    public function views($id): Response{
        Gate::authorize('viewAny', SubArea::class);
        $area = Area::with('estado')->findOrFail($id);
        $areaResource = new AreasResources($area);
        return Inertia::render('panel/SubAreas/indexSubArea', [
            'area' => $areaResource,
        ]);
    }
}
