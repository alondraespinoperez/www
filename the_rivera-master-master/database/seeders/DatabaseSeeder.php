<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory; // importar la clase Factory
use Illuminate\Support\Facades\Hash;//Para encriptar contraseñas
use Illuminate\Support\Str;//Para generar cadenas aleatorias

//IMPORTAR MODELOS
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Cita;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolSeeder::class);//Llamar al seeder de roles y permisos
        // === ADMINISTRADOR ===
       //Crear el administrador
        $admin = Empleado::factory()->create([
            'name' => 'Elisergio Rivera',
            'email' => 'checorivera456@laravel.com',
        ]);
        $admin->assignRole('Administrador');

        //Crear el cliente principal
        $cliente = Cliente::factory()->create([
            'name' => 'Alex Martinez',
            'email' => 'alexmtz2004@laravel.com',
        ]);
        $cliente->assignRole('Cliente');

        //Crear empleados normales
        // $empleados = Empleado::factory(3)->create();
        // foreach ($empleados as $empleado) {
        //     $empleado->assignRole('Empleado'); // Por ejemplo
        // }
        $empleados = Empleado::factory(2)->create()->each(function ($empleado) {
            $empleado->assignRole('Empleado'); // Asignar rol de Empleado a los usuarios creados
        });

        //Crear clientes y asignarles empleados aleatorios
        $clientes = Cliente::factory(20)->create()->each(function ($cliente) use ($empleados) {
            $cliente->empleado_id = $empleados->random()->id;
            $cliente->save();// Guardar el cliente con el empleado asignado
            $cliente->assignRole('Cliente'); // Asignar rol de Cliente a los usuarios creados
        });

        // === CLIENTES ===
        // Creamos los clientes sin guardarlos todavía
        //$clientes = Cliente::factory(29)->make();

        // // Asignamos un empleado aleatorio a cada cliente y luego guardamos
        // foreach ($clientes as $cliente) {
        //     $cliente->empleado_id = $empleados->random()->id; // asignar un empleado aleatorio
        //     $cliente->save();
        // }

        // === SERVICIOS ===
        $servicios = Servicio::factory(10)->create();

        // === CITAS ===
        $citas = Cita::factory(40)->create();

        // === RELACIONES ===
        // CITA - SERVICIOS (muchos a muchos)
        foreach ($citas as $cita) {
            $cita->servicios()->attach($servicios->random(rand(2, 4)));
        }

        // CLIENTE - SERVICIOS (muchos a muchos)
        foreach ($clientes as $cliente) {
            $cliente->servicios()->attach($servicios->random(rand(2, 4)));
        }

        // EMPLEADO - SERVICIOS (muchos a muchos)
        foreach ($empleados as $empleado) {
            $empleado->servicios()->attach($servicios->random(rand(2, 4)));
        }

        // También el empleado principal puede tener servicios
        //$empleadoPrincipal->servicios()->attach($servicios->random(rand(2, 4)));
    }
}
