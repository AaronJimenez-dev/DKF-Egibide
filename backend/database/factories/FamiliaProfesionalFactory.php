<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\FamiliaProfesional;

class FamiliaProfesionalFactory extends Factory
{
    protected $model = FamiliaProfesional::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->company(),
            'codigo_familia' => strtoupper(fake()->unique()->bothify('FAM-###??')),
        ];
    }
}
