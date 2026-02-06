<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Curso;
use App\Models\Ciclos;

class CursoFactory extends Factory
{
    protected $model = Curso::class;

    public function definition(): array
    {
        return [
            'numero' => fake()->unique()->numberBetween(1, 2),
            'ciclo_id' => Ciclos::factory(),
        ];
    }
}
