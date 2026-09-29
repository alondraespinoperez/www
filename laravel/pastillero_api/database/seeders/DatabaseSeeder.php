<?php

namespace Database\Seeders;

use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Médico principal (para pruebas consistentes)
        $medicoPrincipal = Medico::create([
            'nombre' => 'Dr. Juan Perez',
            'especialidad' => 'Cardiologia',
            'cedula' => 'MED-12345',
            'correo' => 'juan@vitacode.com',
            'password' => bcrypt('password123'),
        ]);

        // 5 médicos aleatorios más
        Medico::factory(5)->create();

        // 20 pacientes para el médico principal
        $pacientesPrincipal = Paciente::factory(20)
            ->for($medicoPrincipal)
            ->create();

        // 3 medicamentos para cada paciente del médico principal
        foreach ($pacientesPrincipal as $paciente) {
            Medicamento::factory(3)->for($paciente)->create();
        }

        // 10 pacientes para cada uno de los otros 5 médicos
        Medico::all()->each(function ($medico) {
            if ($medico->correo !== 'juan@vitacode.com') {
                $pacientes = Paciente::factory(10)->for($medico)->create();
                foreach ($pacientes as $p) {
                    Medicamento::factory(2)->for($p)->create();
                }
            }
        });

        $this->command->info('Base de datos poblada: 6 medicos, 70 pacientes, ~170 medicamentos');
    }
}