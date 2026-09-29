<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;  // Para verificar contraseñas
use Symfony\Component\HttpFoundation\Response;  // Para usar códigos de estado HTTP
use App\Models\Cliente;  // Importar el modelo Cliente

class ClienteLoginController extends Controller
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
    }
//     public function store(Request $request)
// {
//     // Validar los datos de entrada
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//         'device_name' => 'required',
//     ]);

//     // Buscar el cliente por correo electrónico
//     $cliente = Cliente::where('email', $request->email)->first();

//     // Verificar si el cliente existe y la contraseña es correcta
//     if (!$cliente || ! Hash::check($request->password, $cliente->password)) {
//         return response()->json([
//             'message' => 'Las credenciales son incorrectas.',
//         ], Response::HTTP_UNPROCESSABLE_ENTITY);
//     }

//     // Generar token
//     return response()->json([
//         'data' => [
//             'attributes' => [
//                 'id' => $cliente->id,
//                 'nombre' => $cliente->name,
//                 'correo' => $cliente->email,
//             ],
//             'token' => $cliente->createToken($request->device_name)->plainTextToken,
//         ],
//     ], Response::HTTP_OK);
// }

    

    public function destroy(Request $request)
    {
        // Revocar el token de acceso del cliente autenticado
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Cierre de sesión exitoso.',
        ], Response::HTTP_OK); // 200
    }
}
