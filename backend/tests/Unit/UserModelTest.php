<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Alumnos;
use App\Models\TutorEmpresa;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_relacion_alumno_es_has_one(): void
    {
        $user = new User();
        $this->assertInstanceOf(HasOne::class, $user->alumno());
    }

    public function test_relacion_tutor_egibide_es_has_one(): void
    {
        $user = new User();
        $this->assertInstanceOf(HasOne::class, $user->tutorEgibide());
    }

    public function test_relacion_instructor_es_has_one(): void
    {
        $user = new User();
        $this->assertInstanceOf(HasOne::class, $user->instructor());
    }

    public function test_se_puede_crear_un_user(): void
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'alumno',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'test@example.com',
            'role' => 'alumno',
        ]);
    }

    public function test_password_esta_hasheado(): void
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'alumno',
        ]);

        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_password_esta_oculto_en_array(): void
    {
        $user = User::factory()->create();

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_user_puede_tener_diferentes_roles(): void
    {
        $roles = ['alumno', 'tutor_egibide', 'tutor_empresa', 'admin'];

        foreach ($roles as $role) {
            $user = User::create([
                'email' => "{$role}@example.com",
                'password' => Hash::make('password'),
                'role' => $role,
            ]);

            $this->assertEquals($role, $user->role);
        }
    }

    public function test_user_alumno_tiene_alumno_asociado(): void
    {
        $user = User::factory()->create(['role' => 'alumno']);

        $alumno = Alumnos::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertNotNull($user->alumno);
        $this->assertEquals($alumno->id, $user->alumno->id);
    }

    public function test_email_verified_at_se_castea_a_datetime(): void
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'alumno',
            'email_verified_at' => '2025-01-15 10:00:00',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->email_verified_at);
    }
}