<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubAreasSeeder extends Seeder{
    public function run(): void{
        $estadoId = DB::table('estados')->where('nombre', 'Activo')->value('id') ?? 1;
        $adminId = DB::table('areas')->where('nombre', 'Administración')->value('id');
        $comercialId = DB::table('areas')->where('nombre', 'Comercial')->value('id');
        DB::table('sub_areas')->insert([
            [
                'nombre' => 'Contabilidad',
                'codigo' => 'ADM-001',
                'area_id' => $adminId,
                'estado_id' => $estadoId,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Recursos Humanos',
                'codigo' => 'ADM-002',
                'area_id' => $adminId,
                'estado_id' => $estadoId,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ventas',
                'codigo' => 'COM-001',
                'area_id' => $comercialId,
                'estado_id' => $estadoId,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
