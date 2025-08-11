<?php

namespace App\Traits;

use Illuminate\Pipeline\Pipeline;

trait Filterable{
    public function scopeFilter($query, array $filters){
        return app(Pipeline::class)
            ->send($query)
            ->through($filters)
            ->thenReturn();
    }
}