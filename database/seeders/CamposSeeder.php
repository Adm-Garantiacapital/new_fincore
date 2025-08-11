<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CamposSeeder extends Seeder
{
    public function run(): void
    {
        $campos = [
            ['nombre' => 'Dni', 'slug' => 'dni', 'tipo' => 'string', 'autocompletar_api' => false],
            ['nombre' => 'Ruc', 'slug' => 'ruc', 'tipo' => 'string', 'autocompletar_api' => false],
            ['nombre' => 'Nombres', 'slug' => 'nombre', 'tipo' => 'string', 'autocompletar_api' => true],
            ['nombre' => 'Apellido paterno', 'slug' => 'apellido_paterno', 'tipo' => 'string', 'autocompletar_api' => true],
            ['nombre' => 'Apellido materno', 'slug' => 'apellido_materno', 'tipo' => 'string', 'autocompletar_api' => true],
            ['nombre' => 'Direccion', 'slug' => 'direccion', 'tipo' => 'string', 'autocompletar_api' => true],
            ['nombre' => 'Fecha de nacimiento', 'slug' => 'fecha_nacimiento', 'tipo' => 'date', 'autocompletar_api' => true],
            ['nombre' => 'Sexo', 'slug' => 'sexo', 'tipo' => 'string', 'autocompletar_api' => true],
            ['nombre' => 'Estado civil', 'slug' => 'estado_civil', 'tipo' => 'string', 'autocompletar_api' => true],
            ['nombre' => 'Tasa esperada', 'slug' => 'tasa_esperada', 'tipo' => 'decimal', 'autocompletar_api' => false],
            ['nombre' => 'Comision', 'slug' => 'comisiones', 'tipo' => 'decimal', 'autocompletar_api' => false],
            ['nombre' => 'Sitio web', 'slug' => 'pagina_web', 'tipo' => 'string', 'autocompletar_api' => false],
            ['nombre' => 'Numero movil', 'slug' => 'telefono', 'tipo' => 'string', 'autocompletar_api' => false],
            ['nombre' => 'Razon social', 'slug' => 'razon_social', 'tipo' => 'string', 'autocompletar_api' => false],
            ['nombre' => 'Nombre comercial', 'slug' => 'nombre_comercial', 'tipo' => 'string', 'autocompletar_api' => false],
            ['nombre' => 'Inicio de actividad', 'slug' => 'inicio_actividad', 'tipo' => 'date', 'autocompletar_api' => false],
            ['nombre' => 'Actividad economica', 'slug' => 'actividad_economica', 'tipo' => 'string', 'autocompletar_api' => false],
            ['nombre' => 'Cargo', 'slug' => 'cargo', 'tipo' => 'string', 'autocompletar_api' => false],
            ['nombre' => 'Correo electronico', 'slug' => 'correo', 'tipo' => 'string', 'autocompletar_api' => false],
            ['nombre' => 'Observaciones', 'slug' => 'observaciones', 'tipo' => 'text', 'autocompletar_api' => false],
            ['nombre' => 'Imagen (carnet)', 'slug' => 'imagen_carnet', 'tipo' => 'image', 'autocompletar_api' => false],
        ];

        DB::table('campos')->insert($campos);
    }
}
