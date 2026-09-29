<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Servicio>
 */
class ServicioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(), // o fake()->sentence(2) si quieres nombres más largos
            'precio' => fake()->numberBetween(50, 1000), // precio en un rango
            'duracion' => fake()->time('H:i:s'), // formato de tiempo
        ];
    }
}
