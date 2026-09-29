<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClienteCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
         return $this->collection->map(function ($cliente) {  // Mapear cada cliente en la colección y estructurarla
            return [
                'id' => $cliente->id,
                'tipo' => 'cliente',
                'atributos' => [
                    'name' => $cliente->name,
                    'email' => $cliente->email,
                    'telefono' => $cliente->telefono,
                    //'password' => $cliente->password,
                    'empleado_id' => $cliente->empleado_id,
                ],
            ];
        })->toArray(); // Convertir la colección mapeada a un array
    }
}
