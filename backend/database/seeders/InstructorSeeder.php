<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstructorSeeder extends Seeder {
    public function run(): void {
        DB::table('instructores')->insert([
            [
                'nombre' => 'Eneko',
                'apellidos' => 'Empresa',
                'telefono' => '600555666',
                'ciudad' => 'Vitoria-Gasteiz',
                'empresa_id' => 1,
                'user_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
