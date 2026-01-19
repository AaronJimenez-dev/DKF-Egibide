<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class CentroSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('centros')->updateOrInsert(
            [
                'nombre' => 'Egibide Arriaga',
                'calle' => 'Arriaga, Vitoria-Gasteiz',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
