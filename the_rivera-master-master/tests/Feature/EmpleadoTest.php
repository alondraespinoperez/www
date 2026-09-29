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
    Empleado::factory(3)->create();//Crear varios empleados de prueba

    $response = $this->getJson('/api/empleados');//Realizar una solicitud GET a la ruta de índice de empleados

    $response->assertStatus(Response::HTTP_OK)//Verificar que la respuesta tenga el estado HTTP 200 (OK)
        ->assertJsonCount(4, 'data')//Verificar que la respuesta contenga 3 empleados en el campo 'data'
         ->assertJsonStructure([//Verificar la estructura JSON de la respuesta
             'data' => [
                [
                'id',
                'tipo',
                'atributos' => [
                    'name',
                    'email',
                    'telefono',
                ],
                ]
            ],
        ]);
});

test('show', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(Empleado::factory()->create()->assignRole('Administrador'));//Simular autenticación como un administrador

    $empleado = Empleado::factory()->create();//Crear un empleado de prueba

    $response = $this->getJson("/api/empleados/{$empleado->id}");//Realizar una solicitud GET a la ruta de mostrar cita

    $response->assertStatus(Response::HTTP_OK)//Verificar que la respuesta tenga el estado HTTP 200 (OK)
        ->assertJsonStructure([//Verificar la estructura JSON de la respuesta
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
        Empleado::factory()->create()->assignRole('Administrador')//Simular autenticación como un administrador
    );

    $data = [//Datos para crear un nuevo empleado
        'name' => $this->faker->name(),
        'email' => $this->faker->unique()->safeEmail(),
        'telefono' => $this->faker->phoneNumber(),
        'password' => 'password123',
        //'empleado_id' => $empleado->id,
    ];

    $response = $this->postJson('/api/empleados/', $data); //Realizar una solicitud POST para crear un nuevo empleado

    $response->assertStatus(Response::HTTP_CREATED); //Verificar que la respuesta tenga el estado HTTP 201 (Creado)

    $this->assertDatabaseHas('empleados', [//Verificar que el empleado se haya creado en la base de datos
        'id' => $empleado->id,
    ]);
});

test('update', function () {
    //Ejecutar el seeder de roles para asegurarse de que los roles estén disponibles
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    // $empleado = Empleado::factory()->create()->assignRole('Administrador');//Crear un empleado de prueba con rol de Administrador
    // Sanctum::actingAs($empleado);//Simular autenticación como el empleado creado
    Sanctum::actingAs(
       Empleado::factory()->create()->assignRole('Administrador')//Simular autenticación como un administrador
     );
    
     $empleado = Empleado::factory()->create();

    $data = [
        'name' => $this->faker->name(),
        'email' => $this->faker()->unique()->safeEmail(),
        'telefono' => $this->faker()->phoneNumber(),
    ];


    $response = $this->putJson("/api/empleados/{$empleado->id}",$data); //Realizar una solicitud PUT para actualizar el empleado
    $response->assertStatus(Response::HTTP_ACCEPTED);//Verificar que la respuesta tenga el estado HTTP 202 (Aceptado)
    $this->assertDatabaseHas('empleados', [//Verificar que el empleado se haya actualizado en la base de datos
    'id' => $empleado->id,
]);

});

test('destroy', function () {//Eliminar un empleado como administrador

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);//Ejecutar el seeder de roles para asegurarse de que los roles estén disponibles

    Sanctum::actingAs(
        Empleado::factory()->create()->assignRole('Administrador')//Simular autenticación como un empleado con rol de Administrador
    );

    //$cita = Cita::factory()->create();//Crear una cita de prueba
    $empleado = Empleado::factory()->create();

    $response = $this->deleteJson("/api/empleados/{$empleado->id}");//Realizar una solicitud DELETE para eliminar el empleado

    $response->assertStatus(Response::HTTP_NO_CONTENT);//Verificar que la respuesta tenga el estado HTTP 204 (Sin Contenido)

    $this->assertDatabaseMissing('empleados', [//Verificar que el empleado se haya eliminado de la base de datos
        'id' => $empleado->id,
    ]);
});

test('destroy_cliente', function () {//Intentar eliminar un empleado como empleado normal

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(
        Empleado::factory()->create()->assignRole('Empleado')//Simular autenticación como un cliente
    );

    //$cita = Cita::factory()->create();//Crear una cita de prueba
    $empleado = Empleado::factory()->create();//Crear un empleado de prueba

    $response = $this->deleteJson("/api/empleados/{$empleado->id}");//Realizar una solicitud DELETE para eliminar la cita

    $response->assertStatus(Response::HTTP_FORBIDDEN);//Verificar que la respuesta tenga el estado HTTP 403 (Prohibido)
});
