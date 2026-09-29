<?php

namespace Database\Factories;

use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

class PacienteFactory extends Factory
{
    protected $model = Paciente::class;

    public function definition(): array
    {
        return [
            'medico_id' => Medico::factory(),
            'nombre' => $this->faker->name(),
            'edad' => $this->faker->numberBetween(20, 90),
            'telefono' => '+52 555 ' . $this->faker->numerify('### ####'),
            'diagnostico' => $this->faker->randomElement([
                'Hipertension arterial',
                'Diabetes tipo 2',
                'Artritis reumatoide',
                'EPOC',
                'Insuficiencia cardiaca',
                'Alzheimer',
                'Parkinson',
            ]),
            'id_pastillero' => 'PILL-' . $this->faker->unique()->numerify('###'),
            'contacto_nombre' => $this->faker->name(),
            'contacto_telefono' => '+52 555 ' . $this->faker->numerify('### ####'),
            'contacto_parentesco' => $this->faker->randomElement([
                'Madre', 'Padre', 'Hijo', 'Hija', 'Esposo', 'Esposa', 'Hermano', 'Hermana',
            ]),
        ];
    }
}