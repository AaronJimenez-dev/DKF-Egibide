<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CicloSeeder extends Seeder {
    public function run(): void {
        DB::table('ciclos')->insert([
            [
                'nombre' => 'DAW (Desarrollo de Aplicaciones Web)',
                'familia_profesional_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
