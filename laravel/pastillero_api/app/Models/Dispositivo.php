<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dispositivo extends Model
{
    use HasFactory;

    protected $table = 'dispositivos';

    protected $fillable = [
        'id_pastillero',
        'ip',
        'ultima_conexion',
        'bateria',
        'online',
    ];

    protected $casts = [
        'ultima_conexion' => 'datetime',
        'online' => 'boolean',
        'bateria' => 'integer',
    ];

    public function eventos(): HasMany
    {
        return $this->hasMany(
            EventoPastillero::class,
            'id_pastillero',
            'id_pastillero'
        );
    }
}