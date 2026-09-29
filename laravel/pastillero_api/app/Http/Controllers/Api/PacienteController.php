<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $pacientes = Paciente::where('medico_id', $request->user()->id)
            ->with('medicamentos')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $pacientes,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer|min:1|max:120',
            'telefono' => 'required|string|max:20',
            'diagnostico' => 'required|string',
            'id_pastillero' => 'required|string|unique:pacientes,id_pastillero',
            'contacto_nombre' => 'nullable|string|max:255',
            'contacto_telefono' => 'nullable|string|max:20',
            'contacto_parentesco' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors(),
            ], 422);
        }

        $paciente = Paciente::create([
            'medico_id' => $request->user()->id,
            'nombre' => $request->nombre,
            'edad' => $request->edad,
            'telefono' => $request->telefono,
            'diagnostico' => $request->diagnostico,
            'id_pastillero' => $request->id_pastillero,
            'contacto_nombre' => $request->contacto_nombre,
            'contacto_telefono' => $request->contacto_telefono,
            'contacto_parentesco' => $request->contacto_parentesco,
        ]);

        return response()->json([
            'message' => 'Paciente creado correctamente',
            'data' => $paciente,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $paciente = Paciente::where('medico_id', $request->user()->id)
            ->with('medicamentos')
            ->find($id);

        if (!$paciente) {
            return response()->json([
                'message' => 'Paciente no encontrado',
            ], 404);
        }

        return response()->json([
            'data' => $paciente,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::where('medico_id', $request->user()->id)
            ->find($id);

        if (!$paciente) {
            return response()->json([
                'message' => 'Paciente no encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer|min:1|max:120',
            'telefono' => 'required|string|max:20',
            'diagnostico' => 'required|string',
            'id_pastillero' => 'required|string|unique:pacientes,id_pastillero,' . $id,
            'contacto_nombre' => 'nullable|string|max:255',
            'contacto_telefono' => 'nullable|string|max:20',
            'contacto_parentesco' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors(),
            ], 422);
        }

        $paciente->update($request->all());

        return response()->json([
            'message' => 'Paciente actualizado correctamente',
            'data' => $paciente,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $paciente = Paciente::where('medico_id', $request->user()->id)
            ->find($id);

        if (!$paciente) {
            return response()->json([
                'message' => 'Paciente no encontrado',
            ], 404);
        }

        $paciente->delete();

        return response()->json([
            'message' => 'Paciente eliminado correctamente',
        ], 200);
    }
}