<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    /** @use HasFactory<\Database\Factories\ServicioFactory> */
    use HasFactory;

    protected $fillable = [ //campos que se pueden asignar masivamente
        'name',
        'precio',
        'duracion',
    ];

    // Relación 1:N (Una cita tiene muchas servicios)
    public function citas(){
         return $this->belongsToMany(Cita::class, 'cita_servicio', 'servicio_id', 'cita_id');
     }

     // Relación N:N (Un cliente puede tener muchos servicios)
     public function clientes(){
        return $this->belongsToMany(Cliente::class, 'cliente_servicio', 'servicio_id', 'cliente_id');
    }

    // Relación N:N (Un empleado puede tener muchos servicios)
     public function empleados(){
        return $this->belongsToMany(Empleado::class, 'empleado_servicio', 'servicio_id', 'empleado_id');
    }
    // Relación N:N (Un servicio puede estar en muchas citas)
    // public function citas() {
    //     return $this->belongsToMany(Cita::class, 'cita_servicio', 'servicio_id', 'cita_id');
    // }
    
    // Relación con la tabla pivote (un servicio puede aparecer en muchas citas)
    // public function detallesCita()
    // {
    // return $this->hasMany(CitaEmpleadoClienteServicio::class);
    // }

}
