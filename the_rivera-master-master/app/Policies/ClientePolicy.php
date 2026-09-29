<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Empleado;

class ClientePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // Determinar si el cliente su cuenta
    public function update(Empleado $user, Cliente $cliente)
     {
        return $user->id === $cliente->empleado_id;// El empleado solo puede actualizar la cuenta de su cliente asignado
     }

    // Determinar si el cliente o empleado puede eliminar la cita
    public function delete(Empleado $user, Cliente $cliente)
    {
         return $user->id === $cliente->empleado_id;// El empleado solo puede eliminar la cuenta de su cliente asignado
    }

}