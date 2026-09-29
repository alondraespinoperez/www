<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
uses(WithFaker::class);

test('index', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(Empleado::factory()->create()->assignRole('Administrador'));

    //Empleado::factory()->create();
    //Cita::factory()->create();
    Cliente::factory(3)->create();
    //Cita::factory(3)->create();

    $response = $this->getJson('/api/clientes');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(3, 'data')
         ->assertJsonStructure([
             'data' => [
                [
                'id',
                'tipo',
                'atributos' => [
                    'name',
                    'email',
                    'telefono',
                    'empleado_id',
                ],
                ]
            ],
        ]);
});

test('show', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(Empleado::factory()->create()->assignRole('Administrador'));//Simular autenticación como un administrador

    //$empleado = Empleado::factory()->create();//Crear un empleado de prueba
    //$cita = Cita::factory()->create();//Crear una cita de prueba
    $cliente = Cliente::factory()->create();

    $response = $this->getJson("/api/clientes/{$cliente->id}");//Realizar una solicitud GET a la ruta de mostrar cita

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                'id',
                'tipo',
                'atributos' => [
                    'name',
                    'email',
                    'telefono',
                ],
            ],
        ]);
});

test('store', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    $empleado = Sanctum::actingAs(
        Empleado::factory()->create()->assignRole('Empleado')//Simular autenticación como un administrador
    );

    //$empleado = Empleado::factory()->create();//Crear un empleado de prueba
    //$servicio = Servicio::factory()->create();//Crear un servicio de prueba

    $data = [//Datos para crear una nueva cita
        'name' => $this->faker->name(),
        'email' => $this->faker->unique()->safeEmail(),
        'telefono' => $this->faker->phoneNumber(),
        'password' => 'password123',
        //'empleado_id' => $empleado->id,
    ];

    $response = $this->postJson('/api/clientes/', $data); //Realizar una solicitud POST para crear una nueva cita

    $response->assertStatus(Response::HTTP_CREATED); //Verificar que la respuesta tenga el estado HTTP 201 (Creado)

    $this->assertDatabaseHas('clientes', [//Verificar que la cita se haya creado en la base de datos
        'empleado_id' => $empleado->id,
    ]);
});

test('update', function () {
    //Ejecutar el seeder de roles para asegurarse de que los roles estén disponibles
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    // Sanctum::actingAs(
    //     $cliente=Cliente::factory()->create()->assignRole('Cliente')//Simular autenticación como un cliente
    // );
    $empleado = Empleado::factory()->create()->assignRole('Administrador');//Crear un empleado de prueba con rol de Administrador
    Sanctum::actingAs($empleado);


    //$empleado = Empleado::factory()->create();//Crear un empleado de prueba
    $cliente = Cliente::factory()->create();//Crear un cliente de prueba

    $data = [ //Datos para actualizar el cliente
        'name' => $this->faker->name(),
        'email' => $this->faker->unique()->safeEmail(),
        'telefono' => $this->faker->phoneNumber(),
    ];

    $response = $this->putJson("/api/clientes/{$cliente->id}", $data); //Realizar una solicitud PUT para actualizar la cita
    $response->assertStatus(Response::HTTP_ACCEPTED);//Verificar que la respuesta tenga el estado HTTP 202 (Aceptado)

    // $this->assertDatabaseHas('clientes', [//Verificar que la cita se haya actualizado en la base de datos
    //     'id' => $cliente->id,
    //     'empleado_id' => $empleado->id,
    // ]);
    $this->assertDatabaseHas('clientes', [
    'id' => $cliente->id,
]);

});

test('destroy', function () {//Eliminar una cita

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);//Ejecutar el seeder de roles para asegurarse de que los roles estén disponibles

    Sanctum::actingAs(
        Empleado::factory()->create()->assignRole('Administrador')//Simular autenticación como un empleado con rol de Administrador
    );

    //$cita = Cita::factory()->create();//Crear una cita de prueba
    $cliente = Cliente::factory()->create();

    $response = $this->deleteJson("/api/clientes/{$cliente->id}");//Realizar una solicitud DELETE para eliminar la cita

    $response->assertStatus(Response::HTTP_NO_CONTENT);//Verificar que la respuesta tenga el estado HTTP 204 (Sin Contenido)

    $this->assertDatabaseMissing('clientes', [//Verificar que la cita haya sido eliminada de la base de datos
        'id' => $cliente->id,
    ]);
});

test('destroy_cliente', function () {//Intentar eliminar una cita como empleado

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(
        Empleado::factory()->create()->assignRole('Empleado')//Simular autenticación como un cliente
    );

    //$cita = Cita::factory()->create();//Crear una cita de prueba
    $cliente = Cliente::factory()->create();//Crear un cliente de prueba

    $response = $this->deleteJson("/api/clientes/{$cliente->id}");//Realizar una solicitud DELETE para eliminar la cita

    $response->assertStatus(Response::HTTP_FORBIDDEN);//Verificar que la respuesta tenga el estado HTTP 403 (Prohibido)
});
