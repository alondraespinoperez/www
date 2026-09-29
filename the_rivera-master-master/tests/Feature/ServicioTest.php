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

    Sanctum::actingAs(Empleado::factory()->create()->assignRole('Administrador'));//Simular autenticación como un administrador
    // Sanctum::actingAs(
    //     Cliente::factory()->create()->assignRole('Cliente')//Simular autenticación como un administrador
    // );
    Servicio::factory(3)->create();//Crear varios servicios de prueba

    $response = $this->getJson('/api/servicios');//Realizar una solicitud GET a la ruta de índice de servicios

    $response->assertStatus(Response::HTTP_OK)//Verificar que la respuesta tenga el estado HTTP 200 (OK)
        ->assertJsonCount(3, 'data')//Verificar que la respuesta contenga 3 servicios en el campo 'data'
         ->assertJsonStructure([//Verificar la estructura JSON de la respuesta
             'data' => [
                [
                'id',
                'tipo',
                'atributos' => [
                    'name',
                    'precio',
                    'duracion',
                ],
                ]
            ],
        ]);
});

test('show', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(Empleado::factory()->create()->assignRole('Administrador'));//Simular autenticación como un administrador

    $servicio = Servicio::factory()->create();//Crear un servicio de prueba

    $response = $this->getJson("/api/servicios/{$servicio->id}");//Realizar una solicitud GET a la ruta de mostrar servicio

    $response->assertStatus(Response::HTTP_OK)//Verificar que la respuesta tenga el estado HTTP 200 (OK)
        ->assertJsonStructure([//Verificar la estructura JSON de la respuesta
            'data' => [
                'id',
                'tipo',
                'atributos' => [
                    'name',
                    'precio',
                    'duracion',
                ],
            ],
        ]);
});

test('store', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    $empleado = Sanctum::actingAs(
        Empleado::factory()->create()->assignRole('Administrador')//Simular autenticación como un administrador
    );

    //$servicio = Servicio::factory()->make();//Crear un servicio de prueba
    $data = [//Datos para crear un nuevo servicio
        'name' => $this->faker->word(),
        'precio' => $this->faker->randomFloat(2, 10, 500),
        'duracion' => $this->faker->numberBetween(15, 180),
    ];

    $response = $this->postJson('/api/servicios/', $data); //Realizar una solicitud POST para crear un nuevo empleado

    $response->assertStatus(Response::HTTP_CREATED); //Verificar que la respuesta tenga el estado HTTP 201 (Creado)

    // $this->assertDatabaseHas('servicios', [//Verificar que el empleado se haya creado en la base de datos
    //     'id' => $servicio->id,
    // ]);
     // Verificar que se haya creado el registro
    $this->assertDatabaseHas('servicios', 
                            ['name' => $response['atributos']['name']]);
});

test('update', function () {
    //Ejecutar el seeder de roles para asegurarse de que los roles estén disponibles
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    // $empleado = Empleado::factory()->create()->assignRole('Administrador');//Crear un empleado de prueba con rol de Administrador
    // Sanctum::actingAs($empleado);//Simular autenticación como el empleado creado
    Sanctum::actingAs(
       Empleado::factory()->create()->assignRole('Administrador')//Simular autenticación como un administrador
     );
    
     $servicio = Servicio::factory()->create();//Crear un servicio de prueba

    $data = [
        'name' => $this->faker->word(),
        'precio' => $this->faker->randomFloat(2, 10, 500),
        'duracion' => $this->faker->numberBetween(15, 180),
    ];


    $response = $this->putJson("/api/servicios/{$servicio->id}",$data); //Realizar una solicitud PUT para actualizar el empleado
    $response->assertStatus(Response::HTTP_ACCEPTED);//Verificar que la respuesta tenga el estado HTTP 202 (Aceptado)
    $this->assertDatabaseHas('servicios', [//Verificar que el empleado se haya actualizado en la base de datos
    'id' => $servicio->id,
]);

});

test('destroy', function () {//Eliminar un empleado como administrador

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);//Ejecutar el seeder de roles para asegurarse de que los roles estén disponibles

    Sanctum::actingAs(
        Empleado::factory()->create()->assignRole('Administrador')//Simular autenticación como un empleado con rol de Administrador
    );

    $servicio = Servicio::factory()->create();//Crear un servicio de prueba
    $response = $this->deleteJson("/api/servicios/{$servicio->id}");//Realizar una solicitud DELETE para eliminar el empleado

    $response->assertStatus(Response::HTTP_NO_CONTENT);//Verificar que la respuesta tenga el estado HTTP 204 (Sin Contenido)

    $this->assertDatabaseMissing('servicios', [//Verificar que el empleado se haya eliminado de la base de datos
        'id' => $servicio->id,
    ]);
});

test('destroy_cliente', function () {//Intentar eliminar un empleado como empleado normal

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(
        Empleado::factory()->create()->assignRole('Empleado')//Simular autenticación como un cliente
    );

    $servicio = Servicio::factory()->create();//Crear un servicio de prueba
    $response = $this->deleteJson("/api/servicios/{$servicio->id}");//Realizar una solicitud DELETE para eliminar la cita

    $response->assertStatus(Response::HTTP_FORBIDDEN);//Verificar que la respuesta tenga el estado HTTP 403 (Prohibido)
});
