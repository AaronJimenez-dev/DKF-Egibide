<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PruebaInicialSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ciclos')->insert([
            [
                'nombre' => 'Desarrollo de Aplicaciones Multiplataforma',
                'grupo' => 'DAM1',
                'familia_profesional_id' => 1,
                'descripcion' => 'Ciclo de DAM',
                'modelo' => '2024',
                'regimen' => 'Diurno',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Desarrollo de Aplicaciones Web',
                'grupo' => 'DAW1',
                'familia_profesional_id' => 1,
                'descripcion' => 'Ciclo de DAW',
                'modelo' => '2024',
                'regimen' => 'Diurno',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('asignaturas')->insert([
            [
                'codigo_asignatura' => 'DAM_M1',
                'nombre_asignatura' => 'Programación',
                'ciclo_id' => 1, // DAM
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo_asignatura' => 'DAM_M2',
                'nombre_asignatura' => 'Bases de Datos',
                'ciclo_id' => 1, // DAM
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo_asignatura' => 'DAW_M1',
                'nombre_asignatura' => 'Desarrollo Web Frontend',
                'ciclo_id' => 2, // DAW
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo_asignatura' => 'DAW_M2',
                'nombre_asignatura' => 'Desarrollo Web Backend',
                'ciclo_id' => 2, // DAW
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('competencias_tec')->insert([
            // Competencias DAM
            [
                'descripcion' => 'Programa y prueba aplicaciones de manera estructurada',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'descripcion' => 'Gestiona bases de datos relacionales y no relacionales',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Competencias DAW
            [
                'descripcion' => 'Desarrolla el frontend de una aplicación web',
                'ciclo_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'descripcion' => 'Desarrolla la lógica de negocio de aplicaciones web backend',
                'ciclo_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
