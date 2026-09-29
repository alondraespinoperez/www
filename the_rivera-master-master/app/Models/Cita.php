<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    /** @use HasFactory<\Database\Factories\CitaFactory> */
    use HasFactory;

    protected $fillable = [ //campos que se pueden asignar masivamente
        'empleado_id',
        //'cliente_id',
        //'servicio_id',
        'fecha_hora',
    ];

    // Una cita puede tener muchos servicios y un servicio puede tener muchas citas
    public function servicios(){
        //return $this->belongsToMany(Servicio::class. 'cita_servicio');
        return $this->belongsToMany(Servicio::class);
    }

    // Una cita pertenece a un cliente  
    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }
   
    // Una cita pertenece a un emlpleado
    public function empleado() {
        return $this->belongsTo(Empleado::class);
        
    }
}
