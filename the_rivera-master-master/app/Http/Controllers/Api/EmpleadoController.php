<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EmpleadoResource; // Importar el recurso EmpleadoResource 
use App\Http\Resources\EmpleadoCollection; // Importar la colección EmpleadoCollection
use App\Http\Requests\StoreEmpleadosRequest; // Importar la request StoreEmpleadosRequest
use App\Http\Requests\UpdateEmpleadosRequest; // Importar la request UpdateEmpleadosRequest
use Symfony\Component\HttpFoundation\Response; // Importar la clase Response para los códigos de estado HTTP
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Importar el trait AuthorizesRequests para la autorización de politicas



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Empleado; // Importar el modelo Empleado

/**
 * @OA\Tag(
 *     name="Empleados",
 *     description="Endpoints para la gestión de empleados"
 * )
 */

class EmpleadoController extends Controller
{
    use AuthorizesRequests;
     // Muestra todos los empleados

     /**
 * @OA\Get(
 *     path="/api/empleados",
 *     summary="Lista todos los empleados",
 *     tags={"Empleados"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de empleados obtenida correctamente",
 *         @OA\JsonContent(type="array",
 *             @OA\Items(ref="#/components/schemas/Empleado")
 *         )
 *     )
 * )
 */

    public function index(){
        $this->authorize('ver empleados');
        // return Categoria::all(); // Devuelve todos los clientes
        return new EmpleadoCollection(Empleado::all());  // Devuelve todos los empleados como recurso API
    }

    /**
 * @OA\Get(
 *     path="/api/empleados/{id}",
 *     summary="Muestra un empleado por ID",
 *     tags={"Empleados"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID del empleado",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Empleado encontrado",
 *         @OA\JsonContent(ref="#/components/schemas/Empleado")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Empleado no encontrado"
 *     )
 * )
 */

    // Muestra un empleado a partir de su id
    public function show(Empleado $empleado){
        $this->authorize('ver empleados');
        // return $categoria; // Devuelve la categoria
        $empleado = $empleado->load('citas');  // Carga las citas relacionadas con el empleado
        return new EmpleadoResource($empleado);  // Devuelve el empleado como recurso API        
    }

    /**
 * @OA\Post(
 *     path="/api/empleados",
 *     summary="Crea un nuevo empleado",
 *     tags={"Empleados"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="Juan Perez"),
 *             @OA\Property(property="email", type="string", example="juan@mail.com"),
 *             @OA\Property(property="telefono", type="string", example="5551234567"),
 *             @OA\Property(property="password", type="string", example="12345678")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Empleado creado correctamente",
 *         @OA\JsonContent(ref="#/components/schemas/Empleado")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error de validación"
 *     )
 * )
 */

    // Almacena un nuevo empleado 
    public function store(StoreEmpleadosRequest $request){  // Usar la request StoreEmpeadosRequest para validar los datos
        $this->authorize('crear empleados');
        $empleado = Empleado::create($request->all());  // Crear un nuevo empleado con los datos validados    
        //$empleado = $request->user()->empleados()->create($request->all());  // Crear un nuevo empleado asociada al usuario autenticado
       
        // Devolver el empleado creado como recurso API con código de estado 201 (creado) 
        return response()->json(new EmpleadoResource($empleado), Response::HTTP_CREATED); 
    }

    /**
 * @OA\Put(
 *     path="/api/empleados/{id}",
 *     summary="Actualiza un empleado existente",
 *     tags={"Empleados"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID del empleado",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Name actualizado"),
 *             @OA\Property(property="email", type="string", example="nuevo@mail.com"),
 *             @OA\Property(property="telefono", type="string", example="5559876543"),
 *             @OA\Property(property="password", type="string", example="passwordNuevo")
 *         )
 *     ),
 *     @OA\Response(
 *         response=202,
 *         description="Empleado actualizado correctamente",
 *         @OA\JsonContent(ref="#/components/schemas/Empleado")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Empleado no encontrado"
 *     )
 * )
 */

     // Actualiza un empleado existente
    public function update(UpdateEmpleadosRequest $request, Empleado $empleado){  // Usar la request UpdateEmpleadosRequest para validar los datos
        $this->authorize('editar empleados');
        //$this->authorize('update', $empleado);  // Autorizar la acción usando la política EmpleadoPolicy
        $empleado->update($request->all());  // Actualizar el empleado con los datos validados

        // Devolver el empleado actualizado como recurso API con código de estado 200 (OK)
        return response()->json(new EmpleadoResource($empleado), Response::HTTP_ACCEPTED);
    }

    /**
 * @OA\Delete(
 *     path="/api/empleados/{id}",
 *     summary="Elimina un empleado",
 *     tags={"Empleados"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID del empleado",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Empleado eliminado correctamente"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Empleado no encontrado"
 *     )
 * )
 */

     // Elimina un empleado existente
    public function destroy(Empleado $empleado){  // Inyectar el empleado a eliminar
        $this->authorize('eliminar empleados');
        //$this->authorize('delete', $empleado);  // Autorizar la acción usando la política EmpleadoPolicy
        $empleado->delete();  // Eliminar el empleado

        // Devolver una respuesta vacía con código de estado 204 (No Content)
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
}
