<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstanciaSeeder extends Seeder {
    public function run(): void {
        DB::table('estancias')->insert([
            [
                'puesto' => 'Desarrollador Web (Laravel)',
                'fecha_inicio' => now()->subDays(14)->toDateString(),
                'fecha_fin' => now()->addMonths(3)->toDateString(),
                'horas_totales' => 400,
                'alumno_id' => 1,
                'tutor_id' => 1,
                'instructor_id' => 1,
                'empresa_id' => 1,
                'curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('estancias')->insert([
            [
                'puesto' => 'Desarrollador Web (Laravel)',
                'fecha_inicio' => now()->subDays(14)->toDateString(),
                'fecha_fin' => now()->addMonths(3)->toDateString(),
                'horas_totales' => 400,
                'alumno_id' => 2,
                'tutor_id' => 1,
                'instructor_id' => 1,
                'empresa_id' => 1,
                'curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
