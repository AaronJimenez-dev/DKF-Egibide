<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetenciaTransSeeder extends Seeder {
    public function run(): void {
        DB::table('competencias_trans')->insert([
            [
                'descripcion' => 'Responsabilidad',
                'nivel' => '1-4',
                'familia_profesional_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
