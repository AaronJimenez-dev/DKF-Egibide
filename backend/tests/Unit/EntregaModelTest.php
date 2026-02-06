<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Entrega;

class EntregaModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_relacion_cuaderno_practicas_es_belongs_to(): void
    {
        $entrega = new Entrega();
        $this->assertInstanceOf(BelongsTo::class, $entrega->cuadernoPracticas());
    }

    public function test_se_puede_crear_una_entrega(): void
    {
        $entrega = Entrega::factory()->create();

        $this->assertDatabaseHas('entregas', [
            'id' => $entrega->id,
            'archivo' => 'entregas/test.pdf',
        ]);
    }

}
