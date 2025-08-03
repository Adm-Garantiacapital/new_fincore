<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run(): void{
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
