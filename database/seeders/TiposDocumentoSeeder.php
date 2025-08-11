<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposDocumentoSeeder extends Seeder{
    public function run(): void{
        DB::table('tipos_documento')->insert([
            ['nombre' => 'DNI'],
            ['nombre' => 'RUC'],
            ['nombre' => 'Carnet de extranjería'],
        ]);
    }
}
