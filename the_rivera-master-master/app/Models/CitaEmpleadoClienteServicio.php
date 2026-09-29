<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaEmpleadoClienteServicio extends Model
{
    use HasFactory;

    protected $table = 'cita_empleado_cliente_servicio'; // nombre exacto de la tabla

    protected $fillable = [
        'cita_id',
        'cliente_id',
        'empleado_id',
        'servicio_id',
    ];

    // Relaciones

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
