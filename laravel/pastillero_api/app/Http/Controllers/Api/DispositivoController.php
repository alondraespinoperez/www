<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dispositivo;
use App\Models\EventoPastillero;
use App\Models\Medicamento;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DispositivoController extends Controller
{
    // ─── Heartbeat ──────────────────────────────────────────
    public function heartbeat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pastillero' => 'required|string',
            'bateria' => 'nullable|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors(),
            ], 422);
        }

        $dispositivo = Dispositivo::updateOrCreate(
            ['id_pastillero' => $request->id_pastillero],
            [
                'ultima_conexion' => now(),
                'online' => true,
                'bateria' => $request->bateria ?? 100,
                'ip' => $request->ip(),
            ]
        );

        return response()->json([
            'message' => 'Heartbeat recibido',
            'data' => $dispositivo,
        ], 200);
    }

    // ─── Evento ─────────────────────────────────────────────
    public function evento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pastillero' => 'required|string',
            'compartimento' => 'required|integer|min:1|max:10',
            'tipo' => 'required|string|in:abierto,cerrado,error,dispensado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors(),
            ], 422);
        }

        Dispositivo::updateOrCreate(
            ['id_pastillero' => $request->id_pastillero],
            [
                'ultima_conexion' => now(),
                'online' => true,
            ]
        );

        $evento = EventoPastillero::create([
            'id_pastillero' => $request->id_pastillero,
            'compartimento' => $request->compartimento,
            'tipo' => $request->tipo,
        ]);

        return response()->json([
            'message' => 'Evento registrado',
            'data' => $evento,
        ], 201);
    }

    // ─── Estado ─────────────────────────────────────────────
    public function estado(Request $request, $idPastillero)
    {
        $dispositivo = Dispositivo::where(
            'id_pastillero',
            $idPastillero
        )->first();

        if (!$dispositivo) {
            return response()->json([
                'message' => 'Dispositivo no registrado',
                'data' => [
                    'id_pastillero' => $idPastillero,
                    'online' => false,
                    'ultima_conexion' => 'Sin conexion',
                    'bateria' => 0,
                    'dispensaciones_hoy' => 0,
                    'compartimentos_abiertos_hoy' => array_fill(0, 10, 0),
                    'ultimos_eventos' => [],
                ],
            ], 200);
        }

        $ultimaConexion = $dispositivo->ultima_conexion;
        $online = $ultimaConexion &&
            Carbon::now()->diffInSeconds($ultimaConexion) < 60;

        if ($dispositivo->online !== $online) {
            $dispositivo->online = $online;
            $dispositivo->save();
        }

        $eventosHoy = EventoPastillero::where(
            'id_pastillero',
            $idPastillero
        )
            ->whereDate('created_at', Carbon::today())
            ->get();

        $compartimentosAbiertosHoy = array_fill(0, 10, 0);
        foreach ($eventosHoy as $evento) {
            if (
                $evento->tipo === 'abierto' &&
                $evento->compartimento >= 1 &&
                $evento->compartimento <= 10
            ) {
                $compartimentosAbiertosHoy[$evento->compartimento - 1]++;
            }
        }

        $dispensacionesHoy = $eventosHoy
            ->where('tipo', 'dispensado')
            ->count();

        $ultimosEventos = EventoPastillero::where(
            'id_pastillero',
            $idPastillero
        )
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($evento) {
                return [
                    'compartimento' => $evento->compartimento,
                    'tipo' => $evento->tipo,
                    'created_at' => $evento->created_at
                        ->timezone('America/Mexico_City')
                        ->format('d/m/Y H:i:s'),
                ];
            });

        $ultimaConexionStr = $ultimaConexion
            ? Carbon::parse($ultimaConexion)
                ->timezone('America/Mexico_City')
                ->diffForHumans()
            : 'Nunca';

        return response()->json([
            'data' => [
                'id_pastillero' => $dispositivo->id_pastillero,
                'online' => $online,
                'ultima_conexion' => $ultimaConexionStr,
                'bateria' => $dispositivo->bateria,
                'dispensaciones_hoy' => $dispensacionesHoy,
                'compartimentos_abiertos_hoy' => $compartimentosAbiertosHoy,
                'ultimos_eventos' => $ultimosEventos,
            ],
        ], 200);
    }

    // ─── Próximo medicamento a dispensar ───────────────────────
    public function proximo(Request $request, $idPastillero)
    {
        $ahora = Carbon::now('America/Mexico_City');
        $horaActual = $ahora->format('H:i');
        
        $horaHace30 = $ahora->copy()->subSeconds(30)->format('H:i');
        $horaEn30 = $ahora->copy()->addSeconds(30)->format('H:i');

        $paciente = Paciente::where('id_pastillero', $idPastillero)->first();

        if (!$paciente) {
            return response()->json([
                'dispensar' => false,
                'mensaje' => 'Pastillero no asignado a ningun paciente',
            ], 200);
        }

        $medicamento = Medicamento::where('paciente_id', $paciente->id)
            ->where('esta_completado', false)
            ->whereBetween('hora_toma', [$horaHace30, $horaEn30])
            ->orderBy('hora_toma', 'asc')
            ->first();

        if (!$medicamento) {
            return response()->json([
                'dispensar' => false,
                'hora_actual' => $horaActual,
                'mensaje' => 'Sin medicamentos para dispensar',
            ], 200);
        }

        $medicamento->esta_completado = true;
        $medicamento->save();

        return response()->json([
            'dispensar' => true,
            'compartimento' => $medicamento->numero_compartimento,
            'medicamento' => $medicamento->nombre,
            'dosis' => $medicamento->dosis,
            'frecuencia' => $medicamento->frecuencia,
            'paciente' => $paciente->nombre,
            'hora_programada' => $medicamento->hora_toma,
            'hora_actual' => $horaActual,
            'medicamento_id' => $medicamento->id,
        ], 200);
    }
}