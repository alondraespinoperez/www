<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Citas API REST con Laravel",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="s22030116@itsch.edu.mx"
 *     )
 * )
 * @OA\Server(url="http://127.0.0.1:8000")
 * @OA\SecurityScheme(
 *     securityScheme="bearer_token",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Sanctum bearer token"
 * )
 */
abstract class Controller
{
    //
}