<?php

namespace Database\Factories;

use App\Models\Entrega;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Alumnos;
use App\Models\Ciclos;

class EntregaFactory extends Factory
{
    protected $model = Entrega::class;

    public function definition(): array
    {
        // 1) Crear Ciclo + Curso
        $ciclo = Ciclos::factory()->create();

        $cursoId = DB::table('cursos')->insertGetId([
            'numero' => 1,
            'ciclo_id' => $ciclo->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2) Crear Tutor (tabla tutores exige user_id)
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

        // 3) Crear Alumno
        $userAlumno = User::factory()->create(['role' => 'alumno']);

        $alumno = Alumnos::factory()->create([
            'user_id' => $userAlumno->id,
        ]);

        // 4) Crear Estancia (campos NOT NULL: fecha_inicio, horas_totales)
        $estanciaId = DB::table('estancias')->insertGetId([
            'alumno_id' => $alumno->id,
            'curso_id' => $cursoId,
            'tutor_id' => $tutorId,
            'puesto' => 'Sin asignar',
            'fecha_inicio' => now()->toDateString(),
            'horas_totales' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5) Crear Cuaderno de prácticas (NOT NULL: estancia_id)
        $cuadernoId = DB::table('cuadernos_practicas')->insertGetId([
            'estancia_id' => $estanciaId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            'archivo' => 'entregas/test.pdf',
            'fecha' => now()->toDateString(),
            'cuaderno_practicas_id' => $cuadernoId,
        ];
    }
}
