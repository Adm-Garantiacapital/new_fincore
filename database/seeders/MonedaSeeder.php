<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonedaSeeder extends Seeder{
    public function run(): void{
        DB::table('monedas')->insert([
            [
                'codigo' => 'PEN',
                'nombre' => 'Sol Peruano',
                'simbolo' => 'S/.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'USD',
                'nombre' => 'Dólar Americano',
                'simbolo' => '$',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
