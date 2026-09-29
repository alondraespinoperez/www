<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ClienteResource; // Importar el recurso ClienteResource 
use App\Http\Resources\ClienteCollection; // Importar la colección ClienteCollection
use App\Http\Requests\StoreClientesRequest; // Importar la request StoreClientesRequest
use App\Http\Requests\UpdateClientesRequest; // Importar la request UpdateClientesRequest
use Symfony\Component\HttpFoundation\Response; // Importar la clase Response para los códigos de estado HTTPz
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Importar el trait AuthorizesRequests para la autorización de politicas
use Illuminate\Support\Facades\Hash;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cliente; // Importar el modelo Cliente

/**
 * @OA\Tag(
 *     name="Clientes",
 *     description="Endpoints para la gestión de clientes"
 * )
 */

class ClienteController extends Controller
{
    use AuthorizesRequests;
    // Muestra todos los clientes

     /**
     * @OA\Get(
     *     path="/api/clientes",
     *     tags={"Clientes"},
     *     summary="Listar todos los clientes",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de clientes obtenida correctamente"
     *     )
     * )
     */
    public function index(){
        $this->authorize('ver clientes');
        // return Categoria::all(); // Devuelve todos los clientes
        return new ClienteCollection(Cliente::all());  // Devuelve todos los clientes como recurso API
    }

    /**
     * @OA\Get(
     *     path="/api/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Mostrar un cliente específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del cliente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     )
     * )
     */
    // Muestra un cliente a partir de su id
    public function show(Cliente $cliente){
        $this->authorize('ver clientes');
        // return $categoria; // Devuelve la categoria
        $cliente = $cliente->load('citas');  // Carga las citas relacionadas con el cliente
        return new ClienteResource($cliente);  // Devuelve el cliente como recurso API
        
    }

    /**
     * @OA\Post(
     *     path="/api/clientes",
     *     tags={"Clientes"},
     *     summary="Crear un nuevo cliente",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","telefono","password","empleado_id"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="email", type="string", example="juan@example.com"),
     *             @OA\Property(property="telefono", type="string", example="555-123-4567"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="empleado_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cliente creado exitosamente"
     *     )
     * )
     */
     // Almacena un nuevo cliente 
    public function store(StoreClientesRequest $request){  // Usar la request StoreClientesRequest para validar los datos
        $this->authorize('crear clientes');
        $cliente = $request->user()->clientes()->create($request->all());//Crear un nuevo cliente asociado al usuario autenticado
        //$cliente = Cliente::create($request->all());  // Crear un nuevo cliente con los datos validados    
       
        // Devolver el cliente creado como recurso API con código de estado 201 (creado) 
        return response()->json(new ClienteResource($cliente), Response::HTTP_CREATED); 
    }

     /**
     * @OA\Put(
     *     path="/api/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Actualizar un cliente existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del cliente a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nombre actualizado"),
     *             @OA\Property(property="email", type="string", example="actualizado@example.com"),
     *             @OA\Property(property="telefono", type="string", example="555-000-1111"),
     *             @OA\Property(property="password", type="string", example="nuevaPassword123"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Cliente actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     )
     * )
     */
     // Actualiza un cliente existente
    public function update(UpdateClientesRequest $request, Cliente $cliente){  // Usar la request UpdateClientesRequest para validar los datos
        $this->authorize('editar clientes');
        //$this->authorize('update', $cliente);  // Autorizar la acción usando la política ClientePolicy
        $cliente->update($request->all());  // Actualizar el cliente con los datos validados

        // Devolver el cliente actualizado como recurso API con código de estado 200 (OK)
        return response()->json(new ClienteResource($cliente), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *     path="/api/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Eliminar un cliente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del cliente a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     )
     * )
     */
     // Elimina un cliente existente
    public function destroy(Cliente $cliente){  // Inyectar el cliente a eliminar   
        $this->authorize('eliminar clientes');
        //$this->authorize('delete', $cliente);  // Autorizar la acción usando la política ClientePolicy
        $cliente->delete();  // Eliminar el empleado

        // Devolver una respuesta vacía con código de estado 204 (No Content)
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
}
