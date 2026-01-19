<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamiliaProfesionalSeeder extends Seeder {
    public function run(): void {
        DB::table('familias_profesionales')->insert([
            [
                'nombre' => 'Informática y Comunicaciones',
                'codigo_familia' => 'IFC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
