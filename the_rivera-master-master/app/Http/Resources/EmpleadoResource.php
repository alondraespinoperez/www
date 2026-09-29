<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,  // ID del empleado
            'tipo' => 'empleado', 
            'atributos' => [  // Estructuramos los atributos del empleado
                'name' => $this->name,
                'email' => $this->email,
                'telefono' => $this->telefono,
                //'password' => $this->password,
            ],
            'relaciones' => [  // Estructuramos las relaciones del empleado
                //'citas' => $this->citas,
                'citas' => CitaResource::collection($this->citas)  // Usamos el recurso CitaResource para formatear las citas relacionadas
                //'citas' => CitaResource::collection($this->whenLoaded('citas')),//whenLoaded('citas') evita errores si las citas no se cargan previamente.
            ],
        ];
    }
}
