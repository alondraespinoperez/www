<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ServicioResource;
use App\Http\Resources\ServicioCollection;
use App\Http\Requests\StoreServiciosRequest;
use App\Http\Requests\UpdateServiciosRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use App\Models\Servicio;

/**
 * @OA\Tag(
 *     name="Servicios",
 *     description="Endpoints para la gestión de servicios"
 * )
 */
class ServicioController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * @OA\Get(
     *     path="/api/servicios",
     *     summary="Mostrar todos los servicios",
     *     tags={"Servicios"},
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de servicios obtenida correctamente"
     *     ),
     *     @OA\Response(response=403, description="No autorizado")
     * )
     */
    public function index(){
        $this->authorize('ver servicios');
        return new ServicioCollection(Servicio::all());
    }

    /**
     * @OA\Get(
     *     path="/api/servicios/{id}",
     *     summary="Mostrar un servicio por ID",
     *     tags={"Servicios"},
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del servicio",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servicio encontrado"
     *     ),
     *     @OA\Response(response=404, description="Servicio no encontrado")
     * )
     */
    public function show(Servicio $servicio){
        $this->authorize('ver servicios');
        $servicio = $servicio->load('citas');
        return new ServicioResource($servicio);
    }

    /**
     * @OA\Post(
     *     path="/api/servicios",
     *     summary="Crear un nuevo servicio",
     *     tags={"Servicios"},
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","precio","duracion"},
     *             @OA\Property(property="name", type="string", example="Corte de cabello"),
     *             @OA\Property(property="precio", type="number", example=199.99),
     *             @OA\Property(property="duracion", type="string", example="01:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Servicio creado correctamente"
     *     ),
     *     @OA\Response(response=403, description="No autorizado"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function store(StoreServiciosRequest $request){
        $this->authorize('crear servicios');
        if (! $request->user() instanceof \App\Models\Empleado) {
            return response()->json([
                'message' => 'Solo los empleados pueden crear servicios.',
            ], 403);
        }

        $servicio = $request->user()->servicios()->create($request->all());
        if($request->has('citas')) {
            $servicio->citas()->attach(json_decode($request->citas));
        }

        return response()->json(new ServicioResource($servicio), 201);
    }

    /**
     * @OA\Put(
     *     path="/api/servicios/{id}",
     *     summary="Actualizar un servicio existente",
     *     tags={"Servicios"},
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del servicio",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Servicio actualizado"),
     *             @OA\Property(property="precio", type="number", example=300),
     *             @OA\Property(property="duracion", type="string", example="00:45:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Servicio actualizado correctamente"
     *     ),
     *     @OA\Response(response=404, description="Servicio no encontrado"),
     *     @OA\Response(response=403, description="No autorizado")
     * )
     */
    public function update(UpdateServiciosRequest $request, Servicio $servicio){
        $this->authorize('editar servicios');
        $servicio->update($request->all());
        return response()->json(new ServicioResource($servicio), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *     path="/api/servicios/{id}",
     *     summary="Eliminar un servicio",
     *     tags={"Servicios"},
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del servicio",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Servicio eliminado correctamente"
     *     ),
     *     @OA\Response(response=403, description="No autorizado"),
     *     @OA\Response(response=404, description="Servicio no encontrado")
     * )
     */
    public function destroy(Servicio $servicio){
        $this->authorize('eliminar servicios');
        $servicio->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}