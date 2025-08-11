<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        #Aceptante
        Permission::create(['name' => 'crear aceptante']);
        Permission::create(['name' => 'editar aceptante']);
        Permission::create(['name' => 'eliminar aceptante']);
        Permission::create(['name' => 'ver aceptante']);
        #Carnet de extrangeria
        Permission::create(['name' => 'crear carnet de extrangeria']);
        Permission::create(['name' => 'editar carnet de extrangeria']);
        Permission::create(['name' => 'eliminar carnet de extrangeria']);
        Permission::create(['name' => 'ver carnet de extrangeria']);
        #Subida de archivos
        Permission::create(['name' => 'crear subir archivos']);
        Permission::create(['name' => 'editar subir archivos']);
        Permission::create(['name' => 'eliminar subir archivos']);
        Permission::create(['name' => 'ver subir archivos']);
        #Moneda
        Permission::create(['name' => 'crear moneda']);
        Permission::create(['name' => 'editar moneda']);
        Permission::create(['name' => 'eliminar moneda']);
        Permission::create(['name' => 'ver moneda']);
        #Condiciones Financieras
        Permission::create(['name' => 'crear condiciones financieras']);
        Permission::create(['name' => 'editar condiciones financieras']);
        Permission::create(['name' => 'eliminar condiciones financieras']);
        Permission::create(['name' => 'ver condiciones financieras']);
        #Tipo de documento
        Permission::create(['name' => 'crear tipo documento']);
        Permission::create(['name' => 'editar tipo documento']);
        Permission::create(['name' => 'eliminar tipo documento']);
        Permission::create(['name' => 'ver tipo documento']);
        #Areas
        Permission::create(['name' => 'crear areas']);
        Permission::create(['name' => 'editar areas']);
        Permission::create(['name' => 'eliminar areas']);
        Permission::create(['name' => 'ver areas']);
        #Sub Areas
        Permission::create(['name' => 'crear sub areas']);
        Permission::create(['name' => 'editar sub areas']);
        Permission::create(['name' => 'eliminar sub areas']);
        Permission::create(['name' => 'ver sub areas']);
        #Detalle Persona
        Permission::create(['name' => 'crear detalle persona']);
        Permission::create(['name' => 'editar detalle persona']);
        Permission::create(['name' => 'eliminar detalle persona']);
        Permission::create(['name' => 'ver detalle persona']);
        #Estados
        Permission::create(['name' => 'crear estado']);
        Permission::create(['name' => 'editar estado']);
        Permission::create(['name' => 'eliminar estado']);
        Permission::create(['name' => 'ver estado']);
        #Personas
        Permission::create(['name' => 'crear persona']);
        Permission::create(['name' => 'editar persona']);
        Permission::create(['name' => 'eliminar persona']);
        Permission::create(['name' => 'ver persona']);
        #Cliente
        Permission::create(['name' => 'crear cliente']);
        Permission::create(['name' => 'editar cliente']);
        Permission::create(['name' => 'eliminar cliente']);
        Permission::create(['name' => 'ver cliente']);
        #Proveedor
        Permission::create(['name' => 'crear proveedor']);
        Permission::create(['name' => 'editar proveedor']);
        Permission::create(['name' => 'eliminar proveedor']);
        Permission::create(['name' => 'ver proveedor']);
        #Persona de contacto
        Permission::create(['name' => 'crear contactos persona']);
        Permission::create(['name' => 'editar contactos persona']);
        Permission::create(['name' => 'eliminar contactos persona']);
        Permission::create(['name' => 'ver contactos persona']);
        #Productos
        Permission::create(['name' => 'crear productos']);
        Permission::create(['name' => 'editar productos']);
        Permission::create(['name' => 'eliminar productos']);
        Permission::create(['name' => 'ver productos']);
        #User
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);
        Permission::create(['name' => 'ver usuarios']);
        # Roles
        Permission::create(['name' =>'crear roles']);
        Permission::create(['name' =>'editar roles']);
        Permission::create(['name' =>'eliminar roles']);
        Permission::create(['name' =>'ver roles']);
        # Permisos
        Permission::create(['name' =>'crear permisos']);
        Permission::create(['name' =>'editar permisos']);
        Permission::create(['name' =>'eliminar permisos']);
        Permission::create(['name' =>'ver permisos']);
    }
}
