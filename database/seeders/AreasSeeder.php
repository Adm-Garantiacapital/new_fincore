<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder{
    public function run(): void{
        $estadoId = DB::table('estados')->where('nombre', 'Activo')->value('id') ?? 1;
        DB::table('areas')->insert([
            [
                'nombre' => 'Administración',
                'estado_id' => $estadoId,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Comercial',
                'estado_id' => $estadoId,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
