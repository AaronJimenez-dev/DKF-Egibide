<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetenciaTecSeeder extends Seeder {
    public function run(): void {
        DB::table('competencias_tec')->insert([
            ['descripcion' => 'Programación backend (Laravel/PHP)', 'ciclo_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
