<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CentroFamiliaProfesionalSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $centroId = DB::table('centros')->where('nombre', 'Egibide Arriaga')->value('id');
        $familiaId = DB::table('familias_profesionales')->where('codigo_familia', 'IFC')->value('id');

        DB::table('centros_familias')->updateOrInsert(
            [
                'centro_id' => $centroId,
                'familia_id' => $familiaId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
