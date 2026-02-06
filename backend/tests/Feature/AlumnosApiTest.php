<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Alumnos;
use App\Models\Ciclos;

class AlumnosApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_permite_acceder_sin_autenticacion(): void
    {
        $response = $this->getJson('/api/alumnos');
        $response->assertStatus(401);
    }

    public function test_listar_alumnos(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Alumnos::factory()->count(3)->create();

        $response = $this->getJson('/api/alumnos');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_crear_alumno_crea_user_alumno_y_estancia(): void
    {
        Sanctum::actingAs(User::factory()->create());

        // 1) Ciclo
        $ciclo = Ciclos::factory()->create();

        // 2) Curso (sin modelo)
        $cursoId = DB::table('cursos')->insertGetId([
            'numero' => 1,
            'ciclo_id' => $ciclo->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3) Tutor (sin modelo)
        $userTutor = User::factory()->create(['role' => 'tutor_egibide']);

        $tutorId = DB::table('tutores')->insertGetId([
            'nombre' => 'Tutor',
            'apellidos' => 'Pruebas',
            'telefono' => '600000000',
            'ciudad' => 'Vitoria',
            'user_id' => $userTutor->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4) Payload real del controller
        $payload = [
            'nombre' => 'Ana',
            'apellidos' => 'García Pérez',
            'telefono' => '600111222',
            'ciudad' => 'Bilbao',
            'curso' => $cursoId,
            'tutor' => $tutorId,
        ];

        $response = $this->postJson('/api/alumnos', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['success' => true]);

        $this->assertDatabaseHas('alumnos', [
            'nombre' => 'Ana',
            'apellidos' => 'García Pérez',
            'telefono' => '600111222',
            'ciudad' => 'Bilbao',
        ]);

        $this->assertDatabaseHas('users', [
            'role' => 'alumno',
        ]);

        $this->assertDatabaseHas('estancias', [
            'curso_id' => $cursoId,
            'tutor_id' => $tutorId,
            'puesto' => 'Sin asignar',
        ]);
    }

    public function test_ver_mi_alumno(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Alumnos::factory()->create([
            'user_id' => $user->id,
            'nombre' => 'Pepe',
        ]);

        $response = $this->getJson('/api/me/alumno');

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Pepe'
                 ]);
    }

    public function test_inicio_alumno_sin_estancia(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Alumnos::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/me/inicio');

        $response->assertStatus(200);
    }
}
