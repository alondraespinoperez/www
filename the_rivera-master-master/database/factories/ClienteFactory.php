<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected static ?string $password = null;
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'telefono' => fake()->phoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            //'empleado_id' => \App\Models\Empleado::all()->random()->id, // Asignar un Empleado aleatorio existente al cliente
             'empleado_id' => \App\Models\Empleado::factory(),
            'remember_token' => Str::random(10),
        ];
    }
}
