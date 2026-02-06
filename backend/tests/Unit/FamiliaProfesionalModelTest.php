<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\FamiliaProfesional;
use App\Models\Ciclos;

class FamiliaProfesionalModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_familia_tiene_muchos_ciclos(): void
    {
        $familia = FamiliaProfesional::factory()->create();
        $ciclo = Ciclos::factory()->create([
            'familia_profesional_id' => $familia->id,
        ]);

        $this->assertTrue($familia->ciclos->contains($ciclo));
    }

    public function test_se_puede_crear_familia_profesional(): void
    {
        $familia = FamiliaProfesional::create([
            'nombre' => 'Sanidad',
            'codigo_familia' => 'SAN',
        ]);

        $this->assertDatabaseHas('familias_profesionales', [
            'id' => $familia->id,
            'nombre' => 'Sanidad',
            'codigo_familia' => 'SAN',
        ]);
    }
}
