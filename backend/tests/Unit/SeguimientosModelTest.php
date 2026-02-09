<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

use App\Models\Seguimiento;
use App\Models\User;
use App\Models\Alumnos;
use App\Models\Ciclos;

class SeguimientosModelTest extends TestCase
{
    use RefreshDatabase;

    private function crearEstancia(): int
    {
        $ciclo = Ciclos::factory()->create();

        $cursoId = DB::table('cursos')->insertGetId([
            'numero' => 1,
            'ciclo_id' => $ciclo->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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

        $userAlumno = User::factory()->create(['role' => 'alumno']);

        $alumno = Alumnos::factory()->create([
            'user_id' => $userAlumno->id,
        ]);

        return DB::table('estancias')->insertGetId([
            'alumno_id' => $alumno->id,
            'curso_id' => $cursoId,
            'tutor_id' => $tutorId,
            'puesto' => 'Sin asignar',
            'fecha_inicio' => now()->toDateString(),
            'horas_totales' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_relacion_estancia_es_belongs_to(): void
    {
        $seguimiento = new Seguimiento();
        $this->assertInstanceOf(BelongsTo::class, $seguimiento->estancia());
    }

    public function test_se_puede_crear_un_seguimiento(): void
    {
        $estanciaId = $this->crearEstancia();

        $seguimiento = Seguimiento::create([
            'accion' => 'Visita empresa',
            'fecha' => now()->toDateString(),
            'descripcion' => 'Todo correcto',
            'via' => 'Presencial',
            'estancia_id' => $estanciaId,
        ]);

        $this->assertDatabaseHas('seguimientos', [
            'id' => $seguimiento->id,
            'accion' => 'Visita empresa',
            'estancia_id' => $estanciaId,
        ]);
    }
}
