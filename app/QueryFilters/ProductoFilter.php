<?php

namespace App\QueryFilters;

class ProductoFilter extends Filter{
    protected function applies(){
        return $this->request->filled('producto_id');
    }
    protected function apply($builder){
        return $builder->where('producto_id', $this->request->producto_id);
    }
}