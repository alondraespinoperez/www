<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;  // Importar el modelo Role
use Spatie\Permission\Models\Permission;  // Importar el modelo Permission

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         /*
        Administrador -> CRUD
        Editor -> CRU
        Usuario -> R
        */
        // Crear roles
        $admin = Role::create(['name' => 'Administrador']);
        $empleado = Role::create(['name' => 'Empleado']);
        $cliente = Role::create(['name' => 'Cliente']);

        // Permisos generales
        Permission::create(['name' => 'ver clientes'])->syncRoles([$admin, $empleado]);
        Permission::create(['name' => 'crear clientes'])->syncRoles([$admin, $empleado]);
        Permission::create(['name' => 'editar clientes'])->syncRoles([$admin, $empleado]);
        Permission::create(['name' => 'eliminar clientes'])->syncRoles([$admin]);

        // Permisos de empleados
        Permission::create(['name' => 'ver empleados'])->syncRoles([$admin,$cliente]);
        Permission::create(['name' => 'crear empleados'])->syncRoles([$admin]);
        Permission::create(['name' => 'editar empleados'])->syncRoles([$admin]);
        Permission::create(['name' => 'eliminar empleados'])->syncRoles([$admin]);

        // Permisos de servicios
        Permission::create(['name' => 'ver servicios'])->syncRoles([$admin, $empleado, $cliente]);
        Permission::create(['name' => 'crear servicios'])->syncRoles([$admin]);
        Permission::create(['name' => 'editar servicios'])->syncRoles([$admin]);
        Permission::create(['name' => 'eliminar servicios'])->syncRoles([$admin]);

        // Permisos de citas
        Permission::create(['name' => 'ver citas'])->syncRoles([$admin, $empleado, $cliente]);
        Permission::create(['name' => 'crear citas'])->syncRoles([$admin, $cliente]);
        Permission::create(['name' => 'editar citas'])->syncRoles([$admin, $cliente]);
        Permission::create(['name' => 'eliminar citas'])->syncRoles([$admin]);
    }
}
