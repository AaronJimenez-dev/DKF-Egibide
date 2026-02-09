<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\CompetenciaTec;
use App\Models\Ciclos;
use App\Models\FamiliaProfesional;

class CompetenciaTecModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_competencia_tec_pertenece_a_un_ciclo(): void
    {
        $familia = FamiliaProfesional::factory()->create();
        $ciclo = Ciclos::factory()->create(['familia_profesional_id' => $familia->id]);

        $competencia = CompetenciaTec::create([
            'descripcion' => 'Desarrollo web',
            'ciclo_id' => $ciclo->id,
        ]);

        $this->assertNotNull($competencia->ciclo);
        $this->assertEquals($ciclo->id, $competencia->ciclo->id);
    }

    public function test_relacion_ciclo_es_belongs_to(): void
    {
        $competencia = new CompetenciaTec();
        $this->assertInstanceOf(BelongsTo::class, $competencia->ciclo());
    }

    public function test_relacion_resultados_aprendizaje_es_belongs_to_many(): void
    {
        $competencia = new CompetenciaTec();
        $this->assertInstanceOf(BelongsToMany::class, $competencia->resultadosAprendizaje());
    }

    public function test_relacion_notas_competencia_tec_es_has_many(): void
    {
        $competencia = new CompetenciaTec();
        $this->assertInstanceOf(HasMany::class, $competencia->notasCompetenciaTec());
    }

    public function test_se_puede_crear_una_competencia_tec(): void
    {
        $familia = FamiliaProfesional::factory()->create();
        $ciclo = Ciclos::factory()->create(['familia_profesional_id' => $familia->id]);

        $competencia = CompetenciaTec::create([
            'descripcion' => 'Implementar bases de datos relacionales',
            'ciclo_id' => $ciclo->id,
        ]);

        $this->assertDatabaseHas('competencias_tec', [
            'id' => $competencia->id,
            'descripcion' => 'Implementar bases de datos relacionales',
            'ciclo_id' => $ciclo->id,
        ]);
    }
}