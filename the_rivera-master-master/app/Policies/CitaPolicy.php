<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Empleado;

class CitaPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // Determinar si el cliente o empleado puede actualizar la receta
    public function update(Cliente $user, Cita $cita)
     {
        return $user->id === $cita->cliente_id; // El cliente solo puede actualizar su propia cita
     }

    // Determinar si el cliente o empleado puede eliminar la cita
    public function delete(Cliente $user, Cita $cita)
    {
         return $user->id === $cita->cliente_id; // El cliente solo puede eliminar su propia cita
    }
}
