<?php

namespace Database\Factories;

use App\Models\Medico;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class MedicoFactory extends Factory
{
    protected $model = Medico::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'especialidad' => $this->faker->randomElement([
                'Cardiologia',
                'Neurologia',
                'Pediatria',
                'Traumatologia',
                'Medicina General',
                'Geriatria',
            ]),
            'cedula' => 'MED-' . $this->faker->unique()->numberBetween(10000, 99999),
            'correo' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
        ];
    }
}