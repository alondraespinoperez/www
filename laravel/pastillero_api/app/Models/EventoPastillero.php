<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoPastillero extends Model
{
    use HasFactory;

    protected $table = 'eventos_pastillero';

    protected $fillable = [
        'id_pastillero',
        'compartimento',
        'tipo',
    ];

    public function dispositivo(): BelongsTo
    {
        return $this->belongsTo(
            Dispositivo::class,
            'id_pastillero',
            'id_pastillero'
        );
    }
}