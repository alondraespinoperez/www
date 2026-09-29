<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importar controladores
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\ClienteLoginController;
use App\Http\Controllers\Api\EmpleadoLoginController;

//Route::post('login', [LoginController::class, 'store']);
Route::post('login/cliente',[ClienteLoginController::class,'store']);
Route::post('login/empleado',[EmpleadoLoginController::class,'store']);  
// Esto ayuda a evitar problemas de CORS en aplicaciones web que consumen esta API 
Route::options('{all:.*}', function(){
    return response()->json();
});

// Rutas protegidas por autenticación 
Route::middleware('auth:sanctum')->group(function () {  
    Route::apiResource('citas', CitaController::class);  // Rutas de citas
    //Route::apiResource('clientes', ClienteController::class)->except(['store']);
    Route::apiResource('clientes', ClienteController::class);  // Rutas de clientes
    Route::apiResource('empleados', EmpleadoController::class);  // Rutas de empleados
    Route::apiResource('servicios', ServicioController::class);  // Rutas de servicios
    //Route::post('logout', [LoginController::class, 'destroy']);  // Ruta para el logout
    Route::post('logout/cliente',[ClienteLoginController::class,'destroy']);
    Route::post('logout/empleado',[EmpleadoLoginController::class,'destroy']);

});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
