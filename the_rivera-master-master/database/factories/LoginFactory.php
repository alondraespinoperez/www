<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash; // para la contraseña
use App\Models\Cliente;              // importa Cliente desde app/Models
use App\Models\Empleado;            // importa Empleado desde app/Models
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Login>
 */
class LoginFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
    'cliente_id' => Cliente::inRandomOrder()->first()->id,
    'empleado_id' => Empleado::inRandomOrder()->first()->id,
    'rol' => fake()->randomElement(['cliente', 'empleado', 'administrador']),
    'usuario' => fake()->userName(),
    'password' => Hash::make('password'),
];
    }
}
