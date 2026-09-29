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

    Sanctum::actingAs(Cliente::factory()->create()->assignRole('Cliente'));

    // $empleados = Empleado::factory()->count(2)->create();
    // $servicios = Servicio::factory()->count(2)->create();
    // $clientes = Cliente::factory()->count(2)->create();
    Empleado::factory()->create();
    Cliente::factory()->create();
    Cita::factory(3)->create();

    $response = $this->getJson('/api/citas');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(3, 'data')
         ->assertJsonStructure([
             'data' => [
                         [
                     'id',
                     'tipo',
                     'atributos' => [
                         'empleado',
                         'cliente',
                         'fecha_hora',
                     ],
                 ],
             ],  
        ]);
});

test('show', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(Cliente::factory()->create()->assignRole('Cliente'));//Simular autenticación como un cliente

    $empleado = Empleado::factory()->create();//Crear un empleado de prueba
    $cita = Cita::factory()->create();//Crear una cita de prueba

    $response = $this->getJson("/api/citas/{$cita->id}");//Realizar una solicitud GET a la ruta de mostrar cita

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                'id',
                'tipo',
                'atributos' => [
                    'empleado',
                    'cliente',
                    'fecha_hora',
                ],
            ],
        ]);
});

test('store', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    $cliente = Sanctum::actingAs(
        Cliente::factory()->create()->assignRole('Cliente')//Simular autenticación como un cliente
    );

    $empleado = Empleado::factory()->create();//Crear un empleado de prueba
    $servicio = Servicio::factory()->create();//Crear un servicio de prueba

    $data = [//Datos para crear una nueva cita
        'empleado_id' => $empleado->id,
        'fecha_hora'  => now()->addDay()->format('Y-m-d H:i:s'),
        'servicios'   => json_encode([$servicio->id]),
    ];

    $response = $this->postJson('/api/citas/', $data); //Realizar una solicitud POST para crear una nueva cita

    $response->assertStatus(Response::HTTP_CREATED); //Verificar que la respuesta tenga el estado HTTP 201 (Creado)

    $this->assertDatabaseHas('citas', [//Verificar que la cita se haya creado en la base de datos
        'empleado_id' => $empleado->id,
    ]);
});

test('update', function () {
    //Ejecutar el seeder de roles para asegurarse de que los roles estén disponibles
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    // Sanctum::actingAs(
    //     $cliente=Cliente::factory()->create()->assignRole('Cliente')//Simular autenticación como un cliente
    // );
    $cliente = Cliente::factory()->create()->assignRole('Cliente');
    Sanctum::actingAs($cliente);


    $empleado = Empleado::factory()->create();//Crear un empleado de prueba
    $cita = Cita::factory()->create(
        ['cliente_id' => $cliente->id]//La cita pertenece al cliente autenticado
    );//Crear una cita de prueba

    $data = [ //Datos para actualizar la cita
        'empleado_id' => $empleado->id,
        'fecha_hora'  => now()->addDays()->format('Y-m-d H:i:s'),
    ];

    $response = $this->putJson("/api/citas/{$cita->id}", $data); //Realizar una solicitud PUT para actualizar la cita

    $response->assertStatus(Response::HTTP_ACCEPTED);//Verificar que la respuesta tenga el estado HTTP 202 (Aceptado)

    $this->assertDatabaseHas('citas', [//Verificar que la cita se haya actualizado en la base de datos
        'id' => $cita->id,
        'empleado_id' => $empleado->id,
    ]);
});

test('destroy', function () {//Eliminar una cita

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);//Ejecutar el seeder de roles para asegurarse de que los roles estén disponibles

    Sanctum::actingAs(
        Empleado::factory()->create()->assignRole('Administrador')//Simular autenticación como un empleado con rol de Administrador
    );

    $cita = Cita::factory()->create();//Crear una cita de prueba

    $response = $this->deleteJson("/api/citas/{$cita->id}");//Realizar una solicitud DELETE para eliminar la cita

    $response->assertStatus(Response::HTTP_NO_CONTENT);//Verificar que la respuesta tenga el estado HTTP 204 (Sin Contenido)

    $this->assertDatabaseMissing('citas', [//Verificar que la cita haya sido eliminada de la base de datos
        'id' => $cita->id,
    ]);
});

test('destroy_cliente', function () {//Intentar eliminar una cita como cliente

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(
        Cliente::factory()->create()->assignRole('Cliente')//Simular autenticación como un cliente
    );

    $cita = Cita::factory()->create();//Crear una cita de prueba

    $response = $this->deleteJson("/api/citas/{$cita->id}");//Realizar una solicitud DELETE para eliminar la cita

    $response->assertStatus(Response::HTTP_FORBIDDEN);//Verificar que la respuesta tenga el estado HTTP 403 (Prohibido)
});
