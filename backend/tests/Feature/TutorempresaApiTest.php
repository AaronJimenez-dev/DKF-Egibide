<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Alumnos;
use App\Models\Ciclos;
use App\Models\FamiliaProfesional;
use App\Models\Empresas;
use App\Models\TutorEmpresa;
use App\Models\Estancia;

class TutorEmpresaApiTest extends TestCase
{
    use RefreshDatabase;

    private function crearInstructorConEstructura(): array
    {
        // Usuario instructor
        $userInstructor = User::factory()->create(['role' => 'tutor_empresa']);

        // Empresa
        $empresa = Empresas::factory()->create();

        // Instructor
        $instructor = TutorEmpresa::create([
            'nombre' => 'Carlos',
            'apellidos' => 'Martínez López',
            'telefono' => '600111222',
            'ciudad' => 'Bilbao',
            'empresa_id' => $empresa->id,
            'user_id' => $userInstructor->id,
        ]);

        // Estructura para alumnos
        $familia = FamiliaProfesional::factory()->create();
        $ciclo = Ciclos::factory()->create(['familia_profesional_id' => $familia->id]);

        $cursoId = DB::table('cursos')->insertGetId([
            'numero' => 1,
            'ciclo_id' => $ciclo->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userTutor = User::factory()->create(['role' => 'tutor_egibide']);
        $tutorId = DB::table('tutores')->insertGetId([
            'nombre' => 'Tutor',
            'apellidos' => 'Egibide',
            'telefono' => '600000000',
            'ciudad' => 'Vitoria',
            'user_id' => $userTutor->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return compact('userInstructor', 'instructor', 'empresa', 'cursoId', 'tutorId', 'ciclo');
    }

    public function test_requiere_autenticacion(): void
    {
        $this->getJson('/api/tutorEmpresa/inicio')
            ->assertStatus(401);
    }

    public function test_inicio_instructor(): void
    {
        $datos = $this->crearInstructorConEstructura();
        Sanctum::actingAs($datos['userInstructor']);

        // Crear alumno con estancia activa
        $userAlumno = User::factory()->create(['role' => 'alumno']);
        $alumno = Alumnos::factory()->create(['user_id' => $userAlumno->id]);

        Estancia::create([
            'alumno_id' => $alumno->id,
            'curso_id' => $datos['cursoId'],
            'tutor_id' => $datos['tutorId'],
            'instructor_id' => $datos['instructor']->id,
            'empresa_id' => $datos['empresa']->id,
            'puesto' => 'Desarrollador Junior',
            'fecha_inicio' => now()->subDays(10),
            'fecha_fin' => now()->addDays(20),
            'horas_totales' => 400,
        ]);

        $response = $this->getJson('/api/tutorEmpresa/inicio');

        $response->assertOk()
            ->assertJsonStructure([
                'instructor' => [
                    'nombre',
                    'apellidos',
                    'telefono',
                    'ciudad',
                    'email',
                ],
                'counts' => [
                    'alumnos_asignados',
                ],
            ])
            ->assertJsonFragment([
                'nombre' => 'Carlos',
                'apellidos' => 'Martínez López',
                'alumnos_asignados' => 1,
            ]);
    }

    public function test_inicio_instructor_sin_estancias_activas(): void
    {
        $datos = $this->crearInstructorConEstructura();
        Sanctum::actingAs($datos['userInstructor']);

        $response = $this->getJson('/api/tutorEmpresa/inicio');

        $response->assertOk()
            ->assertJsonFragment([
                'alumnos_asignados' => 0,
            ]);
    }

    public function test_inicio_instructor_cuenta_solo_estancias_activas(): void
    {
        $datos = $this->crearInstructorConEstructura();
        Sanctum::actingAs($datos['userInstructor']);

        // Alumno con estancia pasada (no debe contar)
        $userAlumno1 = User::factory()->create(['role' => 'alumno']);
        $alumno1 = Alumnos::factory()->create(['user_id' => $userAlumno1->id]);

        Estancia::create([
            'alumno_id' => $alumno1->id,
            'curso_id' => $datos['cursoId'],
            'tutor_id' => $datos['tutorId'],
            'instructor_id' => $datos['instructor']->id,
            'empresa_id' => $datos['empresa']->id,
            'puesto' => 'Desarrollador',
            'fecha_inicio' => now()->subDays(60),
            'fecha_fin' => now()->subDays(30),
            'horas_totales' => 400,
        ]);

        // Alumno con estancia activa (debe contar)
        $userAlumno2 = User::factory()->create(['role' => 'alumno']);
        $alumno2 = Alumnos::factory()->create(['user_id' => $userAlumno2->id]);

        Estancia::create([
            'alumno_id' => $alumno2->id,
            'curso_id' => $datos['cursoId'],
            'tutor_id' => $datos['tutorId'],
            'instructor_id' => $datos['instructor']->id,
            'empresa_id' => $datos['empresa']->id,
            'puesto' => 'Desarrollador',
            'fecha_inicio' => now()->subDays(10),
            'fecha_fin' => null,
            'horas_totales' => 400,
        ]);

        $response = $this->getJson('/api/tutorEmpresa/inicio');

        $response->assertOk()
            ->assertJsonFragment([
                'alumnos_asignados' => 1,
            ]);
    }

    public function test_inicio_instructor_falla_si_usuario_no_tiene_instructor(): void
    {
        $user = User::factory()->create(['role' => 'alumno']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/tutorEmpresa/inicio');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'El usuario no tiene instructor (tutor de empresa) asociado.',
            ]);
    }

    public function test_obtiene_alumnos_asignados_instructor(): void
    {
        $datos = $this->crearInstructorConEstructura();
        Sanctum::actingAs($datos['userInstructor']);

        // Crear alumno asignado
        $userAlumno = User::factory()->create(['role' => 'alumno']);
        $alumno = Alumnos::factory()->create([
            'user_id' => $userAlumno->id,
            'nombre' => 'Ana',
            'apellidos' => 'García',
        ]);

        Estancia::create([
            'alumno_id' => $alumno->id,
            'curso_id' => $datos['cursoId'],
            'tutor_id' => $datos['tutorId'],
            'instructor_id' => $datos['instructor']->id,
            'empresa_id' => $datos['empresa']->id,
            'puesto' => 'Desarrollador',
            'fecha_inicio' => now(),
            'horas_totales' => 400,
        ]);

        $response = $this->getJson("/api/tutorEmpresa/{$datos['instructor']->id}/alumnos");

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'nombre' => 'Ana',
                'apellidos' => 'García',
            ]);
    }

    public function test_instructor_no_ve_alumnos_de_otros_instructores(): void
    {
        $datos = $this->crearInstructorConEstructura();
        Sanctum::actingAs($datos['userInstructor']);

        // Crear otro instructor
        $userOtroInstructor = User::factory()->create(['role' => 'tutor_empresa']);
        $otroInstructor = TutorEmpresa::create([
            'nombre' => 'Otro',
            'apellidos' => 'Instructor',
            'telefono' => '600222333',
            'ciudad' => 'Vitoria',
            'empresa_id' => $datos['empresa']->id,
            'user_id' => $userOtroInstructor->id,
        ]);

        // Alumno del otro instructor
        $userAlumno = User::factory()->create(['role' => 'alumno']);
        $alumno = Alumnos::factory()->create(['user_id' => $userAlumno->id]);

        Estancia::create([
            'alumno_id' => $alumno->id,
            'curso_id' => $datos['cursoId'],
            'tutor_id' => $datos['tutorId'],
            'instructor_id' => $otroInstructor->id,
            'empresa_id' => $datos['empresa']->id,
            'puesto' => 'Desarrollador',
            'fecha_inicio' => now(),
            'horas_totales' => 400,
        ]);

        $response = $this->getJson("/api/tutorEmpresa/{$datos['instructor']->id}/alumnos");

        $response->assertOk()
            ->assertJsonCount(0);
    }

    public function test_obtiene_perfil_instructor(): void
    {
        $datos = $this->crearInstructorConEstructura();
        Sanctum::actingAs($datos['userInstructor']);

        $response = $this->getJson('/api/me/tutor-empresa');

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'nombre',
                'apellidos',
                'email',
                'tipo',
            ])
            ->assertJsonFragment([
                'nombre' => 'Carlos',
                'apellidos' => 'Martínez López',
                'email' => $datos['userInstructor']->email,
            ]);
    }

    public function test_falla_obtener_perfil_si_no_es_instructor(): void
    {
        $user = User::factory()->create(['role' => 'alumno']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/me/tutor-empresa');

        $response->assertStatus(500);
    }
}