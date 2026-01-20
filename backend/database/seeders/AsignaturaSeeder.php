<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignaturaSeeder extends Seeder {
    public function run(): void {
        DB::table('asignaturas')->insert([
            [
                'codigo_asignatura' => 'DWES',
                'nombre_asignatura' => 'Desarrollo de aplicaciones entorno servidor',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo_asignatura' => 'DAW',
                'nombre_asignatura' => 'Despliegue de aplicaciones web',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo_asignatura' => 'DWEC',
                'nombre_asignatura' => 'Desarrollo de aplicaciones enotorno servidor',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo_asignatura' => 'DIW',
                'nombre_asignatura' => 'Diseño de interfaces web',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo_asignatura' => 'SI',
                'nombre_asignatura' => 'Sistemas informaticos',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
