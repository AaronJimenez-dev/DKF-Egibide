<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CompetenciaTec;
use App\Models\Ciclos;

class CompetenciaTecFactory extends Factory
{
    protected $model = CompetenciaTec::class;

    public function definition(): array
    {
        return [
            'descripcion' => fake()->sentence(),
            'ciclo_id' => Ciclos::factory(),
        ];
    }
}