<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIker = DB::table('users')->where('email', 'iker@demo.com')->value('id');

        DB::table('alumnos')->insert([
        [
            'nombre' => 'Iker',
            'apellidos' => 'Hernaez',
            'telefono' => '600111222',
            'ciudad' => 'Vitoria-Gasteiz',
            'user_id' => $userIker,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nombre' => 'Naia',
            'apellidos' => 'Garrido',
            'telefono' => '620111222',
            'ciudad' => 'Vitoria-Gasteiz',
            'user_id' => '2',
            'created_at' => now(),
            'updated_at' => now(),
        ]
        ]);
        
    }
}
