<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresas;

class EmpresasFactory extends Factory
{
    protected $model = Empresas::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->company(),
            'cif' => strtoupper(fake()->bothify('?########')),
            'direccion' => fake()->address(),
            'telefono' => fake()->numerify('6########'),
            'email' => fake()->unique()->companyEmail(),
        ];
    }
}
