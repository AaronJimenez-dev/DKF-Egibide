<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\FamiliaProfesional;

class FamiliasProfesionalesApiTest extends TestCase
{
    use RefreshDatabase;

    private function authUser(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function test_requiere_autenticacion(): void
    {
        $this->getJson('/api/familiasProfesionales')
            ->assertStatus(401);
    }

    public function test_lista_familias_profesionales(): void
    {
        $this->authUser();

        FamiliaProfesional::factory()->count(3)->create();

        $this->getJson('/api/familiasProfesionales')
            ->assertOk()
            ->assertJsonCount(3);
    }

    public function test_devuelve_campos_correctos(): void
    {
        $this->authUser();

        $familia = FamiliaProfesional::factory()->create([
            'nombre' => 'Informática',
            'codigo_familia' => 'IFC',
        ]);

        $this->getJson('/api/familiasProfesionales')
            ->assertOk()
            ->assertJsonFragment([
                'nombre' => 'Informática',
                'codigo_familia' => 'IFC',
            ]);
    }
}
