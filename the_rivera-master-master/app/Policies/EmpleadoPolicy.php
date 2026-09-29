<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Empleado;


class EmpleadoPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
     // Determinar si el empleado puede actualizar su cuenta
    public function update(Empleado $user, Empleado $empleado)
     {
        return $user->id === $empleado->id;
     }

    // Determinar si empleado puede eliminar su cuenta
    public function delete(Empleado $user, Empleado $empleado)
    {
         return $user->id === $empleado->id;
    }
}
