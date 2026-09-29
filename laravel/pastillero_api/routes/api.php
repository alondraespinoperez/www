<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\MedicamentoController;
use Illuminate\Support\Facades\Route;

// Ruta fallback para evitar el error "Route [login] not defined."
Route::get('/login', function () {
    return response()->json([
        'message' => 'No autenticado. Token invalido o ausente.',
    ], 401);
})->name('login');

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren token)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Pacientes - CRUD
    Route::get('/pacientes', [PacienteController::class, 'index']);
    Route::post('/pacientes', [PacienteController::class, 'store']);
    Route::get('/pacientes/{id}', [PacienteController::class, 'show']);
    Route::put('/pacientes/{id}', [PacienteController::class, 'update']);
    Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy']);

    // Medicamentos - CRUD
    Route::get('/pacientes/{pacienteId}/medicamentos', [MedicamentoController::class, 'index']);
    Route::post('/pacientes/{pacienteId}/medicamentos', [MedicamentoController::class, 'store']);
    Route::put('/medicamentos/{id}', [MedicamentoController::class, 'update']);
    Route::delete('/medicamentos/{id}', [MedicamentoController::class, 'destroy']);
    Route::patch('/medicamentos/{id}/toggle', [MedicamentoController::class, 'toggle']);
});