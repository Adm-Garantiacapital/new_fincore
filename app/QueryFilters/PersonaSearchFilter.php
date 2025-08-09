<?php

namespace App\QueryFilters;

class PersonaSearchFilter extends Filter{
    protected function applies(){
        return $this->request->filled('search');
    }
    protected function apply($builder){
        $search = $this->request->search;
        return $builder->where(function ($query) use ($search) {
            $query->whereHas('cliente', function ($clienteQuery) use ($search) {
                $clienteQuery->where('dni', 'LIKE', "%{$search}%")
                        ->orWhere('nombre', 'LIKE', "%{$search}%")
                        ->orWhere('apellido_paterno', 'LIKE', "%{$search}%")
                        ->orWhere('apellido_materno', 'LIKE', "%{$search}%")
                        ->orWhere('telefono', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('proveedor', function ($proveedorQuery) use ($search) {
                $proveedorQuery->where('ruc', 'LIKE', "%{$search}%")
                             ->orWhere('razon_social', 'LIKE', "%{$search}%")
                             ->orWhere('nombre_comercial', 'LIKE', "%{$search}%");
            });
        });
    }
}