<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CompetenciaTransversal;
use App\Models\FamiliaProfesional;

class CompetenciaTransversalFactory extends Factory
{
    protected $model = CompetenciaTransversal::class;

    public function definition(): array
    {
        return [
            'descripcion' => fake()->sentence(),
            'nivel' => fake()->randomElement(['Básico', 'Intermedio', 'Avanzado']),
            'familia_profesional_id' => FamiliaProfesional::factory(),
        ];
    }
}