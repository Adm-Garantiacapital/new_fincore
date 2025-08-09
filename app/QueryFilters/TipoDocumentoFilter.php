<?php

namespace App\QueryFilters;

class TipoDocumentoFilter extends Filter{
    protected function applies(){
        return $this->request->filled('tipo_documento_id');
    }
    protected function apply($builder){
        return $builder->where('tipo_documento_id', $this->request->tipo_documento_id);
    }
}