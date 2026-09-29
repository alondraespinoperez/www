<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; //Importar el trait HasRoles para manejar roles y permisos

class Cliente extends Authenticatable
{
    protected $guard_name = 'web';// Definir el guard para Spatie Roles y Permisos
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
     use HasApiTokens, HasFactory, Notifiable, HasRoles;// Usar el trait HasRoles para manejar roles y permisos

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [ //campos que se pueden asignar masivamente
        'name',
        'email',
        'telefono',
        'password',
        //'empleado_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación 1:N (Un cliente tiene muchas citas)
     public function citas(){
         return $this->hasMany(Cita::class);
     }

     // Relación N:N (Un cliente puede tener muchos servicios)
     public function servicios(){
        //return $this->belongsToMany(Servicio::class, 'cliente_servicio', 'cliente_id', 'servicio_id');
        return $this->belongsToMany(Servicio::class); 
    }

    // Relación inversa N:1 (Muchos clientes pertenecen a un empleado)
    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }   
}
