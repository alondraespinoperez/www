<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CitaResource; // Importar el recurso CitaResource 
use App\Http\Resources\CitaCollection; // Importar la colección CitaCollection
use App\Http\Requests\StoreCitasRequest; // Importar la request StoreRecetasRequest
use App\Http\Requests\UpdateCitasRequest; // Importar la request UpdateRecetasRequest
use Symfony\Component\HttpFoundation\Response; // Importar la clase Response para los códigos de estado HTTP
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Importar el trait AuthorizesRequests para la autorización de politicas


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cita; // Importar el modelo Cita

/**
 * @OA\Tag(
 *     name="Citas",
 *     description="Endpoints para gestionar citas"
 * )
 */

class CitaController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\Get(
     *    path="/api/citas",
     *    summary="Consultar todas las citas",
     *    description="Retorna todas las citas",
     *    tags={"Citas"},
     *    security={{"bearer_token":{}}},
     *    @OA\Response(
     *       response=200,
     *      description="Operación exitosa",
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="No autorizado"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="No se encontraron recetas"
     *   ),
     *   @OA\Response(
     *    response=405,
     *    description="Método no permitido"
     *   )
     * )
     */


    // Muestra todas las cit6as
    public function index(){
        // return Receta::all(); // Devuelve todas las recetas
        // return Receta::with('categoria', 'etiquetas', 'user')->get(); // Carga las relaciones categoria, etiquetas y user
        $this->authorize('ver citas');
        $citas = Cita::with('cliente', 'empleado','servicios')->get();// Carga las relaciones clientes, empleados y servicios
        return CitaResource::collection($citas); // Devuelve todas las citas como recurso API
    }

     /**
     * @OA\Get(
     *     path="/api/citas/{id}",
     *     summary="Mostrar una cita específica",
     *     tags={"Citas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la cita",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cita encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="empleado_id", type="integer", example=3),
     *             @OA\Property(property="cliente_id", type="integer", example=7),
     *             @OA\Property(property="fecha_hora", type="string", example="2025-03-10 10:00:00")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Cita no encontrada")
     * )
     */

    // Muestra una cita a partir de su id
    public function show(Cita $cita){
        // return $receta; // Devuelve la receta
        //return $receta->load('categoria', 'etiquetas', 'user'); // Carga las relaciones categoria, etiquetas y user
        $this->authorize('ver citas');
        $cita = $cita->load('cliente', 'empleado', 'servicios'); // Carga las relaciones clientes, empleados y servicios
        return new CitaResource($cita); // Devuelve la receta como recurso API 
    }

    /**
     * @OA\Post(
     *     path="/api/citas",
     *     summary="Crear una nueva cita",
     *     tags={"Citas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"empleado_id", "cliente_id", "fecha_hora"},
     *             @OA\Property(property="empleado_id", type="integer", example=1),
     *             @OA\Property(property="cliente_id", type="integer", example=2),
     *             @OA\Property(property="fecha_hora", type="string", format="date-time", example="2025-01-15 14:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cita creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="empleado_id", type="integer", example=1),
     *             @OA\Property(property="cliente_id", type="integer", example=2),
     *             @OA\Property(property="fecha_hora", type="string", example="2025-01-15 14:00:00")
     *         )
     *     )
     * )
     */

    // Almacena una nueva cita 
    public function store(StoreCitasRequest $request){  // Usar la request StoreCitasRequest para validar los datos
        $this->authorize('crear citas'); 
        $cita = $request->user()->citas()->create($request->all());  // Crear una nueva cita asociada al usuario autenticado
        $cita->servicios()->attach(json_decode($request->servicios));  // Asociar lps servicios a la cita (decodificar el JSON recibido)
        //$cita = Cita::create($request->all());  // Crear una nueva cita con los datos validado

        // Devolver la cita creada como recurso API con código de estado 201 (creado)
        return response()->json(new CitaResource($cita), Response::HTTP_CREATED); 
    }

     /**
     * @OA\Put(
     *     path="/api/citas/{id}",
     *     summary="Actualizar una cita existente",
     *     tags={"Citas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la cita",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="empleado_id", type="integer", example=3),
     *             @OA\Property(property="cliente_id", type="integer", example=1),
     *             @OA\Property(property="fecha_hora", type="string", example="2025-02-20 09:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Cita actualizada correctamente",
     *     ),
     *     @OA\Response(response=404, description="Cita no encontrada")
     * )
     */

    // Actualiza una cita existente
    public function update(UpdateCitasRequest $request, Cita $cita){  // Usar la request UpdateCitasRequest para validar los datos
        $this->authorize('editar citas');
        $this->authorize('update', $cita);  // Autorizar la acción usando la política CitaPolicy
        $cita->update($request->all());  // Actualizar la cita con los datos validados

        if($clientes = json_decode($request->clientes)){  // Si se reciben clientes, decodificar el JSON
            $cita->clientes()->sync($clientes);  // Sincronizar los clientes (eliminar los que no están y agregar los nuevos)
        }

        if($empleados = json_decode($request->empleados)){  // Si se reciben empleados, decodificar el JSON
            $cita->empleados()->sync($empleados);  // Sincronizar los empleados (eliminar los que no están y agregar los nuevos)
        }

        if($servicios = json_decode($request->servicios)){  // Si se reciben servicios, decodificar el JSON
            $cita->servicios()->sync($servicios);  // Sincronizar los servicios (eliminar los que no están y agregar los nuevos)
        }

        // Devolver la cita actualizada como recurso API con código de estado 200 (OK)
        return response()->json(new CitaResource($cita), Response::HTTP_ACCEPTED);
    }

     /**
     * @OA\Delete(
     *     path="/api/citas/{id}",
     *     summary="Eliminar una cita",
     *     tags={"Citas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la cita",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Cita eliminada correctamente"),
     *     @OA\Response(response=404, description="Cita no encontrada")
     * )
     */
    
     // Elimina una cita existente
    public function destroy(Cita $cita){  // Inyectar la cita a eliminar
        $this->authorize('eliminar citas');
        //$this->authorize('delete', $cita);  // Autorizar la acción usando la política CitaPolicy
        $cita->delete();  // Eliminar la cita

        // Devolver una respuesta vacía con código de estado 204 (No Content)
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
}
