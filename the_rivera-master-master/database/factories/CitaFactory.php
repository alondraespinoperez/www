<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CitaFactory extends Factory
{
    public function definition(): array
    {
        return [

            'empleado_id' => \App\Models\Empleado::factory(),
            'cliente_id' => \App\Models\Cliente::factory(),
            'fecha_hora' => fake()->dateTimeBetween('+1 days', '+1 month'),
        ];
    }
}