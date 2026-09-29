<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Servicio;
use App\Models\Empleado;

class ServicioPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    //  // Determinar si el empleado puede actualizar el servicio
    // public function update(Empleado $user, Servicio $servicio)
     //{
       //  return $empleado->id === $servicio->empleado_id;
     //}

    // // Determinar si el empleado puede eliminar el servicio
     //public function delete(Empleado $user, Servicio $servicio)
     //{
        //return $empleado->id === $servicio->empleado_id;
    //}
      public function update(Empleado $empleado, Servicio $servicio)
     {
        // Verifica si el empleado está asociado al servicio
         return $servicio->empleados()->where('empleado_id', $empleado->id)->exists();
     }

    // /**
    //  * Determina si el empleado puede eliminar el servicio.
    //  */
     public function delete(Empleado $empleado, Servicio $servicio)
     {
        // Igual que el método update
         return $servicio->empleados()->where('empleado_id', $empleado->id)->exists();
     }
}
