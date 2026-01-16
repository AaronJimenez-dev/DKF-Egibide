<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignaturaSeeder extends Seeder {
    public function run(): void {
        DB::table('asignaturas')->insert([
            [
                'codigo_asignatura' => 'FCT',
                'nombre_asignatura' => 'Formación en Centros de Trabajo',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
