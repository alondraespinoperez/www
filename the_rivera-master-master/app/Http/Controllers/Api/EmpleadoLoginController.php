<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;  // Para verificar contraseñas
use Symfony\Component\HttpFoundation\Response;  // Para usar códigos de estado HTTP
use App\Models\Empleado;  // Importar el modelo Empleado    

class EmpleadoLoginController extends Controller
{
     public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required',
            'dispositivo' => 'required',
        ]);
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

        // Revocar el token de acceso del empleado autenticado
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Cierre de sesión exitoso.',
        ], Response::HTTP_OK); // 200
    }
}
