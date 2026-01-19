<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultadoAprendizajeSeeder extends Seeder {
    public function run(): void {
        DB::table('resultados_aprendizaje')->insert([
            [
                'descripcion' => 'RA1: Integración en la empresa y organización.',
                'asignatura_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
