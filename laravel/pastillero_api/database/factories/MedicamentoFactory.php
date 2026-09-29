<?php

namespace Database\Factories;

use App\Models\Medicamento;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicamentoFactory extends Factory
{
    protected $model = Medicamento::class;

    public function definition(): array
    {
        return [
            'paciente_id' => Paciente::factory(),
            'nombre' => $this->faker->randomElement([
                'Losartan', 'Metformina', 'Ibuprofeno', 'Paracetamol',
                'Omeprazol', 'Atorvastatina', 'Amlodipino', 'Aspirina',
                'Insulina', 'Levotiroxina', 'Metotrexato', 'Salbutamol',
            ]),
            'dosis' => $this->faker->randomElement([
                '50 mg', '100 mg', '500 mg', '850 mg', '1 pastilla', '2 pastillas',
                '10 ml', '5 mg',
            ]),
            'hora_toma' => $this->faker->time('H:i'),
            'numero_compartimento' => $this->faker->numberBetween(1, 10),
            'frecuencia' => $this->faker->randomElement([
                'Cada 8 horas',
                'Cada 12 horas',
                'Cada 24 horas',
                'Cada 48 horas',
                'Semanal',
                'Diario',
            ]),
            'esta_completado' => $this->faker->boolean(30),
        ];
    }
}