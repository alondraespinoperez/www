<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Medico extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'medicos';

    protected $fillable = [
        'nombre',
        'especialidad',
        'cedula',
        'correo',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class);
    }
}