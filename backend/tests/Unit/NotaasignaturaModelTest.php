<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

use App\Models\NotaAsignatura;
use App\Models\Alumnos;
use App\Models\User;
use App\Models\Ciclos;
use App\Models\FamiliaProfesional;

class NotaAsignaturaModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_nota_asignatura_pertenece_a_un_alumno(): void
    {
        $user = User::factory()->create(['role' => 'alumno']);
        $alumno = Alumnos::factory()->create(['user_id' => $user->id]);

        $familia = FamiliaProfesional::factory()->create();
        $ciclo = Ciclos::factory()->create(['familia_profesional_id' => $familia->id]);

        $asignaturaId = DB::table('asignaturas')->insertGetId([
            'codigo_asignatura' => 'BBDD',
            'nombre_asignatura' => 'Bases de Datos',
            'ciclo_id' => $ciclo->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $nota = NotaAsignatura::create([
            'alumno_id' => $alumno->id,
            'asignatura_id' => $asignaturaId,
            'nota' => 8.5,
            'anio' => 2025,
        ]);

        $this->assertNotNull($nota->alumno);
        $this->assertEquals($alumno->id, $nota->alumno->id);
    }

    public function test_relacion_alumno_es_belongs_to(): void
    {
        $nota = new NotaAsignatura();
        $this->assertInstanceOf(BelongsTo::class, $nota->alumno());
    }

    public function test_relacion_asignatura_es_belongs_to(): void
    {
        $nota = new NotaAsignatura();
        $this->assertInstanceOf(BelongsTo::class, $nota->asignatura());
    }

    public function test_se_puede_crear_una_nota_asignatura(): void
    {
        $user = User::factory()->create(['role' => 'alumno']);
        $alumno = Alumnos::factory()->create(['user_id' => $user->id]);

        $familia = FamiliaProfesional::factory()->create();
        $ciclo = Ciclos::factory()->create(['familia_profesional_id' => $familia->id]);

        $asignaturaId = DB::table('asignaturas')->insertGetId([
            'codigo_asignatura' => 'PROG',
            'nombre_asignatura' => 'Programación',
            'ciclo_id' => $ciclo->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $nota = NotaAsignatura::create([
            'alumno_id' => $alumno->id,
            'asignatura_id' => $asignaturaId,
            'nota' => 7.5,
            'anio' => 2025,
        ]);

        $this->assertDatabaseHas('notas_asignatura', [
            'id' => $nota->id,
            'alumno_id' => $alumno->id,
            'asignatura_id' => $asignaturaId,
            'nota' => 7.5,
            'anio' => 2025,
        ]);
    }

    public function test_nota_se_castea_a_decimal(): void
    {
        $user = User::factory()->create(['role' => 'alumno']);
        $alumno = Alumnos::factory()->create(['user_id' => $user->id]);

        $familia = FamiliaProfesional::factory()->create();
        $ciclo = Ciclos::factory()->create(['familia_profesional_id' => $familia->id]);

        $asignaturaId = DB::table('asignaturas')->insertGetId([
            'codigo_asignatura' => 'BBDD',
            'nombre_asignatura' => 'Bases de Datos',
            'ciclo_id' => $ciclo->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $nota = NotaAsignatura::create([
            'alumno_id' => $alumno->id,
            'asignatura_id' => $asignaturaId,
            'nota' => 8.5,
            'anio' => 2025,
        ]);

        $this->assertEquals('8.50', $nota->nota);
    }
}