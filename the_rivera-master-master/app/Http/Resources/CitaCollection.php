<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CitaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($cita) {  // Mapear cada cita en la colección y estructurarla
            return [
                'id' => $cita->id,
                'tipo' => 'cita',
                'atributos' => [
                    'empleado_id' => $cita->empleado_id,
                    'cliente_id' => $cita->cliente_id,
                    'fecha_hora' => $cita->fecha_hora,
                    //'servicio_id' => $cita->servicio_id,
                ],
            ];
        })->toArray(); // Convertir la colección mapeada a un array
    }
}
