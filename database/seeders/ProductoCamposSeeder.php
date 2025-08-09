<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoCamposSeeder extends Seeder{
    public function run(): void{
        $productoFactoringId = DB::table('productos')->where('nombre', 'Factoring')->value('id');
        $productoConfirmingId = DB::table('productos')->where('nombre', 'Confirming')->value('id');
        $dniId = DB::table('tipos_documento')->where('nombre', 'DNI')->value('id');
        $rucId = DB::table('tipos_documento')->where('nombre', 'RUC')->value('id');
        $ceId  = DB::table('tipos_documento')->where('nombre', 'Carnet de extranjería')->value('id');

        // Factoring - DNI
        $this->asignarCampos($productoFactoringId, $dniId, [
            'Dni', 'Nombres', 'Apellido paterno', 'Apellido materno', 'Direccion',
            'Fecha de nacimiento', 'Sexo', 'Estado civil', 'Tasa esperada',
            'Comision', 'Sitio web', 'Numero movil'
        ]);

        // Factoring - RUC
        $this->asignarCampos($productoFactoringId, $rucId, [
            'Ruc', 'Razon social', 'Nombre comercial', 'Inicio de actividad',
            'Direccion', 'Actividad economica', 'Tasa esperada', 'Comision',
        ]);
        $this->asignarCampos($productoFactoringId, $rucId, [
            'Dni', 'Nombres', 'Apellido paterno', 'Apellido materno', 'Cargo',
            'Correo electronico', 'Sitio web', 'Numero movil', 'Observaciones'
        ], 'Datos adicionales');

        // Factoring - Carnet
        $this->asignarCampos($productoFactoringId, $ceId, [
            'Imagen (carnet)', 'Nombres', 'Apellido paterno', 'Apellido materno', 'Direccion',
            'Fecha de nacimiento', 'Sexo', 'Estado civil', 'Tasa esperada',
            'Comision', 'Sitio web', 'Numero movil'
        ]);

        // Confirming - RUC
        $this->asignarCampos($productoConfirmingId, $rucId, [
            'Ruc', 'Razon social', 'Nombre comercial', 'Inicio de actividad',
            'Direccion', 'Actividad economica', 'Tasa esperada', 'Comision'
        ]);
        $this->asignarCampos($productoConfirmingId, $rucId, [
            'Dni', 'Nombres', 'Apellido paterno', 'Apellido materno', 'Cargo',
            'Correo electronico', 'Sitio web', 'Numero movil', 'Observaciones'
        ], 'Datos de contacto');
    }

    private function asignarCampos($productoId, $tipoDocumentoId, $nombresCampos, $grupo = null){
        foreach ($nombresCampos as $orden => $nombreCampo) {
            $campoId = DB::table('campos')->where('nombre', $nombreCampo)->value('id');

            if ($campoId) {
                DB::table('producto_campos')->insert([
                    'producto_id' => $productoId,
                    'tipo_documento_id' => $tipoDocumentoId,
                    'campo_id' => $campoId,
                    'grupo' => $grupo,
                    'orden' => $orden,
                ]);
            }
        }
    }
}
