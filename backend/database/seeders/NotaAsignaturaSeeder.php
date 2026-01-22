<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Alumnos;
use App\Models\Asignatura;
use App\Models\NotaAsignatura;

class NotaAsignaturaSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $alumnos = Alumnos::all();
        $asignaturas = Asignatura::all();
        $anioActual = date('Y');

        if ($alumnos->isEmpty() || $asignaturas->isEmpty()) {
            $this->command->info('No hay alumnos o asignaturas en la base de datos. Ejecuta los seeders correspondientes primero.');
            return;
        }

        foreach ($alumnos as $alumno) {
            foreach ($asignaturas as $asignatura) {
                $notaExistente = NotaAsignatura::where('alumno_id', $alumno->id)
                    ->where('asignatura_id', $asignatura->id)
                    ->where('anio', $anioActual)
                    ->exists();

                if (!$notaExistente) {
                    NotaAsignatura::create([
                        'nota' => rand(50, 100) / 10,
                        'anio' => $anioActual,
                        'alumno_id' => $alumno->id,
                        'asignatura_id' => $asignatura->id,
                    ]);
                }
            }
        }

        $this->command->info('Notas de asignatura creadas exitosamente.');
    }
}
