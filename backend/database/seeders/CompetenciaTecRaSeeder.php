<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetenciaTecRaSeeder extends Seeder {
    public function run(): void {
        DB::table('competencia_tec_ra')->insert([
            [
                'competencia_tec_id' => 1,
                'resultado_aprendizaje_id' => 1
            ],
        ]);
    }
}
