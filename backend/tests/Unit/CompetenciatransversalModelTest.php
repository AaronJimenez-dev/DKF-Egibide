<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\CompetenciaTransversal;
use App\Models\FamiliaProfesional;

class CompetenciaTransversalModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_competencia_trans_pertenece_a_una_familia_profesional(): void
    {
        $familia = FamiliaProfesional::factory()->create();

        $competencia = CompetenciaTransversal::create([
            'descripcion' => 'Trabajo en equipo',
            'nivel' => 'Avanzado',
            'familia_profesional_id' => $familia->id,
        ]);

        $this->assertNotNull($competencia->familiaProfesional);
        $this->assertEquals($familia->id, $competencia->familiaProfesional->id);
    }

    public function test_relacion_familia_profesional_es_belongs_to(): void
    {
        $competencia = new CompetenciaTransversal();
        $this->assertInstanceOf(BelongsTo::class, $competencia->familiaProfesional());
    }

    public function test_relacion_notas_competencia_trans_es_has_many(): void
    {
        $competencia = new CompetenciaTransversal();
        $this->assertInstanceOf(HasMany::class, $competencia->notasCompetenciaTrans());
    }

    public function test_se_puede_crear_una_competencia_transversal(): void
    {
        $familia = FamiliaProfesional::factory()->create();

        $competencia = CompetenciaTransversal::create([
            'descripcion' => 'Comunicación efectiva',
            'nivel' => 'Intermedio',
            'familia_profesional_id' => $familia->id,
        ]);

        $this->assertDatabaseHas('competencias_trans', [
            'id' => $competencia->id,
            'descripcion' => 'Comunicación efectiva',
            'nivel' => 'Intermedio',
            'familia_profesional_id' => $familia->id,
        ]);
    }

    public function test_nivel_es_opcional(): void
    {
        $familia = FamiliaProfesional::factory()->create();

        $competencia = CompetenciaTransversal::create([
            'descripcion' => 'Autonomía',
            'familia_profesional_id' => $familia->id,
        ]);

        $this->assertDatabaseHas('competencias_trans', [
            'descripcion' => 'Autonomía',
        ]);
    }
}