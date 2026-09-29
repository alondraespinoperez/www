<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'cedula' => 'required|string|unique:medicos,cedula',
            'email' => 'required|email|unique:medicos,correo',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors(),
            ], 422);
        }

        $medico = Medico::create([
            'nombre' => $request->nombre,
            'especialidad' => $request->especialidad,
            'cedula' => $request->cedula,
            'correo' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $medico->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Medico registrado correctamente',
            'token' => $token,
            'user' => $medico,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors(),
            ], 422);
        }

        $medico = Medico::where('correo', $request->email)->first();

        if (!$medico || !Hash::check($request->password, $medico->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $token = $medico->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesion exitoso',
            'token' => $token,
            'user' => $medico,
        ], 200);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesion cerrada correctamente',
        ], 200);
    }
}