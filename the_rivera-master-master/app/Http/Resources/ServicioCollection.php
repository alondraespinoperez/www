<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServicioCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($servicio) {  // Mapear cada servicio en la colección y estructurarla
            return [
                'id' => $servicio->id,
                'tipo' => 'servicio',
                'atributos' => [
                    'name' => $servicio->name,
                    'precio' => $servicio->precio,
                    'duracion' => $servicio->duracion,
                ],
            ];
        })->toArray(); // Convertir la colección mapeada a un array
    }
}
