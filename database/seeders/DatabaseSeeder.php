<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void{
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            EstadosSeeder::class,
            AreasSeeder::class,
            SubAreasSeeder::class,
            TiposDocumentoSeeder::class,
            MonedaSeeder::class,
            CamposSeeder::class,
            ProductosSeeder::class,
            ProductoCamposSeeder::class,
        ]);
    }
}
