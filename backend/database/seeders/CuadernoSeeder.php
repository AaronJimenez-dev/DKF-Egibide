<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuadernoSeeder extends Seeder {
    public function run(): void {
        DB::table('cuadernos_practicas')->insert([
            [
                'archivo' => null,
                'archivo_vacio' => 'plantilla_cuaderno.pdf',
                'estancia_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('entregas')->insert([
            [
                'archivo' => 'entrega_1.pdf',
                'fecha' => now()->subDays(7)->toDateString(),
                'cuaderno_practicas_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('notas_cuaderno')->insert([
            ['nota' => 8.50, 'cuaderno_practicas_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
