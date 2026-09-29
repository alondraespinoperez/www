<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [  // Estructuramos la respuesta de la receta como recurso API
            'id' => $this->id,
            'tipo' => 'cita',
            'atributos' => [
                'empleado' => $this->empleado->name,  // Nombre del empleado asociado a la cita
                'cliente' => $this->cliente->name,    // Nombre del cliente
                'fecha_hora' => $this->fecha_hora, // Fecha y hora de la cita
                'servicios' => $this->servicios->pluck('name'),  // Lista de nombres de los servicios asociados a la cita separadas por comas 
                // pluck extrae los nombres de las etiquetas y implode los une en una cadena separada por comas
            ],
        ];  
    }
}
