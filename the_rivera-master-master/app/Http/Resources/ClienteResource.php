<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,  // ID del cliente
            'tipo' => 'cliente', 
            'atributos' => [  // Estructuramos los atributos del cliente
                'name' => $this->name,
                'email' => $this->email,
                'telefono' => $this->telefono,
                //'password' => $this->password,
                'empleado' => $this->empleado->name,  // Nombre del empleado asociado a la cita
                //'empleado_id' => $this->empleado_id,
            ],
            'relaciones' => [  // Estructuramos las relaciones del cliente
                //'citas' => $this->citas,
                'citas' => CitaResource::collection($this->citas)  // Usamos el recurso CitaResource para formatear las citas relacionadas
                //'citas' => CitaResource::collection($this->whenLoaded('citas')),//whenLoaded('citas') evita errores si las citas no se cargan previamente.
            ],
        ];
    }
}
