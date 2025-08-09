<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder{
    public function run(): void{
        DB::table('productos')->insert([
            ['nombre' => 'Factoring', 'descripcion' => 'Producto financiero de factoring', 'estado_id' => 1],
            ['nombre' => 'Confirming', 'descripcion' => 'Producto financiero de confirming', 'estado_id' => 1],
        ]);
    }
}
