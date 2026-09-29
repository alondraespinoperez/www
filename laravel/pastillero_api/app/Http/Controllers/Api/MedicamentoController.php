<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicamento;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicamentoController extends Controller
{
    public function index(Request $request, $pacienteId)
    {
        $paciente = Paciente::where('medico_id', $request->user()->id)
            ->find($pacienteId);

        if (!$paciente) {
            return response()->json([
                'message' => 'Paciente no encontrado',
            ], 404);
        }

        $medicamentos = Medicamento::where('paciente_id', $pacienteId)
            ->orderBy('hora_toma', 'asc')
            ->get();

        return response()->json([
            'data' => $medicamentos,
        ], 200);
    }

    public function store(Request $request, $pacienteId)
    {
        $paciente = Paciente::where('medico_id', $request->user()->id)
            ->find($pacienteId);

        if (!$paciente) {
            return response()->json([
                'message' => 'Paciente no encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'dosis' => 'required|string|max:100',
            'hora_toma' => 'required|string|max:5',
            'numero_compartimento' => 'required|integer|min:1|max:10',
            'frecuencia' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors(),
            ], 422);
        }

        $medicamento = Medicamento::create([
            'paciente_id' => $pacienteId,
            'nombre' => $request->nombre,
            'dosis' => $request->dosis,
            'hora_toma' => $request->hora_toma,
            'numero_compartimento' => $request->numero_compartimento,
            'frecuencia' => $request->frecuencia,
            'esta_completado' => false,
        ]);

        return response()->json([
            'message' => 'Medicamento creado correctamente',
            'data' => $medicamento,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $medicamento = Medicamento::find($id);

        if (!$medicamento) {
            return response()->json([
                'message' => 'Medicamento no encontrado',
            ], 404);
        }

        // Verificar que el paciente pertenece al médico autenticado
        $paciente = Paciente::where('medico_id', $request->user()->id)
            ->find($medicamento->paciente_id);

        if (!$paciente) {
            return response()->json([
                'message' => 'No autorizado',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'dosis' => 'required|string|max:100',
            'hora_toma' => 'required|string|max:5',
            'numero_compartimento' => 'required|integer|min:1|max:10',
            'frecuencia' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors(),
            ], 422);
        }

        $medicamento->update($request->all());

        return response()->json([
            'message' => 'Medicamento actualizado correctamente',
            'data' => $medicamento,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $medicamento = Medicamento::find($id);

        if (!$medicamento) {
            return response()->json([
                'message' => 'Medicamento no encontrado',
            ], 404);
        }

        $paciente = Paciente::where('medico_id', $request->user()->id)
            ->find($medicamento->paciente_id);

        if (!$paciente) {
            return response()->json([
                'message' => 'No autorizado',
            ], 403);
        }

        $medicamento->delete();

        return response()->json([
            'message' => 'Medicamento eliminado correctamente',
        ], 200);
    }

    public function toggle(Request $request, $id)
    {
        $medicamento = Medicamento::find($id);

        if (!$medicamento) {
            return response()->json([
                'message' => 'Medicamento no encontrado',
            ], 404);
        }

        $paciente = Paciente::where('medico_id', $request->user()->id)
            ->find($medicamento->paciente_id);

        if (!$paciente) {
            return response()->json([
                'message' => 'No autorizado',
            ], 403);
        }

        $medicamento->esta_completado = !$medicamento->esta_completado;
        $medicamento->save();

        return response()->json([
            'message' => 'Estado actualizado',
            'data' => $medicamento,
        ], 200);
    }
}