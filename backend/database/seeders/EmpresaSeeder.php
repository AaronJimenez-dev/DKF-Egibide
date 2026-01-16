<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('empresas')->insert([
            [
                'cif' => 'B12345678',
                'nombre' => 'Tech Vitoria SL',
                'telefono' => '945111222',
                'email' => 'rrhh@techvitoria.com',
                'direccion' => 'C/ Florida 10, Vitoria-Gasteiz',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
