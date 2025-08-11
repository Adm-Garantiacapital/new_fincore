<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Http\Request;

abstract class Filter{
    protected $request;
    public function __construct(){
        $this->request = request();
    }
    public function handle($request, Closure $next){
        if (!$this->applies()) {
            return $next($request);
        }
        return $next($this->apply($request));
    }
    protected abstract function applies();
    protected abstract function apply($builder);
}