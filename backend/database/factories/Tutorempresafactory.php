<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TutorEmpresa;
use App\Models\User;
use App\Models\Empresas;

class TutorEmpresaFactory extends Factory
{
    protected $model = TutorEmpresa::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellidos' => fake()->lastName() . ' ' . fake()->lastName(),
            'telefono' => fake()->optional()->numerify('#########'),
            'ciudad' => fake()->optional()->city(),
            'empresa_id' => Empresas::factory(),
            'user_id' => User::factory(['role' => 'tutor_empresa']),
        ];
    }
}