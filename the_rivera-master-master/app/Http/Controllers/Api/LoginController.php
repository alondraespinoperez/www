<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;  // Para verificar contraseñas
use Symfony\Component\HttpFoundation\Response;  // Para usar códigos de estado HTTP
use App\Models\Cliente;  // Importar el modelo Cliente
use App\Models\Empleado;  // Importar el modelo Empleado

class LoginController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required',
            'dispositivo' => 'required',
        ]);
   
        // Buscar el cliente por correo electrónico
        $cliente = Cliente::where('email', $request->correo)->first();
   
        // Verificar si el clinete existe y la contraseña es correcta
        if (!$cliente || ! Hash::check($request->contraseña, $cliente->password)) {
            return response()->json([
                'message' => 'Las credenciales son incorrectas.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }

        // Generar un token de acceso para el cliente
        return response()->json([
            'data' => [
                'attributes' => [
                    'id' => $cliente->id,
                    'nombre' => $cliente->name,
                    'correo' => $cliente->email,
                ],
                'token' => $cliente->createToken($request->dispositivo)->plainTextToken,
            ],
        ],Response::HTTP_OK); // 200

            //Buscar el empleado por correo electrónico
            $empleado = Empleado::where('email', $request->correo)->first();

            //Verificar si el empleado existe y la contraseña es correcta
            if (!$empleado || ! Hash::check($request->contraseña, $empleado->password)) {
                return response()->json([
                    'message' => 'Las credenciales son incorrectas.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
            }

            //Genera un token de acceso para el empleado
            return response()->json([
                'data' => [
                    'attributes' => [
                        'id' => $empleado->id,
                        'nombre' => $empleado->name,
                        'correo' => $empleado->email,
                ],
                'token' => $empleado->createToken($request->dispositivo)->plainTextToken,
            ],
        ],Response::HTTP_OK); // 200
    }
    

    public function destroy(Request $request)
    {
        // Revocar el token de acceso del cliente autenticado
        $request->cliente()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Cierre de sesión exitoso.',
        ], Response::HTTP_OK); // 200

        // Revocar el token de acceso del empleado autenticado
        $request->empleado()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Cierre de sesión exitoso.',
        ], Response::HTTP_OK); // 200
    }
// //  public function store(Request $request)
//     {
//         // Validar los datos de entrada
//         $request->validate([
//             'correo' => 'required|email',
//             'contraseña' => 'required',
//             'dispositivo' => 'required',
//         ]);

//         // PRIMERO buscar como CLIENTE
//         $cliente = Cliente::where('email', $request->correo)->first();
        
//         if ($cliente && Hash::check($request->contraseña, $cliente->password)) {
//             // Login exitoso como CLIENTE
//             return response()->json([
//                 'data' => [
//                     'attributes' => [
//                         'id' => $cliente->id,
//                         'nombre' => $cliente->name,
//                         'correo' => $cliente->email,
//                         'telefono' => $cliente->telefono,
//                         'tipo_usuario' => 'cliente',
//                     ],
//                     'token' => $cliente->createToken($request->dispositivo)->plainTextToken,
//                 ],
//             ], Response::HTTP_OK);
//         }

//         // SI NO es cliente, buscar como EMPLEADO
//         $empleado = Empleado::where('email', $request->correo)->first();
        
//         if ($empleado && Hash::check($request->contraseña, $empleado->password)) {
//             // Login exitoso como EMPLEADO
//             return response()->json([
//                 'data' => [
//                     'attributes' => [
//                         'id' => $empleado->id,
//                         'nombre' => $empleado->name,
//                         'correo' => $empleado->email,
//                         'telefono' => $empleado->telefono,
//                         'tipo_usuario' => 'empleado',
//                         //'es_admin' => $empleado->es_admin, // Descomenta cuando tengas este campo
//                     ],
//                     'token' => $empleado->createToken($request->dispositivo)->plainTextToken,
//                 ],
//             ], Response::HTTP_OK);
//         }

//         // Si llega aquí, las credenciales son incorrectas para ambos
//         return response()->json([
//             'message' => 'Las credenciales son incorrectas.',
//         ], Response::HTTP_UNPROCESSABLE_ENTITY);
//     }

    // public function destroy(Request $request)
    // {
    //     // Revocar el token de acceso del usuario autenticado (funciona para ambos)
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json([
    //         'message' => 'Cierre de sesión exitoso.',
    //     ], Response::HTTP_OK);
    // }
}