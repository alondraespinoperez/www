<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,  // ID del servicio
            'tipo' => 'servicio', 
            'atributos' => [  // Estructuramos los atributos del servicio
                'name' => $this->name,
                'precio' => $this->precio,
                'duracion' => $this->duracion,
            ],
            'relaciones' => [  // Estructuramos las relaciones del servicio
                //'citas' => $this->citas,
                'citas' => CitaResource::collection($this->citas)  // Usamos el recurso CitaResource para formatear las citas relacionadas
                //'citas' => CitaResource::collection($this->whenLoaded('citas')),//whenLoaded('citas') evita errores si las citas no se cargan previamente.
            ],
        ];
    }
}
