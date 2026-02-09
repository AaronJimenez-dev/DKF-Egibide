<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_con_credenciales_validas(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'alumno',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'email',
                    'role',
                ],
            ])
            ->assertJsonFragment([
                'email' => 'test@example.com',
                'role' => 'alumno',
            ]);

        $this->assertNotEmpty($response->json('token'));
    }

    public function test_login_con_credenciales_invalidas(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Credenciales inválidas.',
            ]);
    }

    public function test_login_con_email_inexistente(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'noexiste@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Credenciales inválidas.',
            ]);
    }

    public function test_login_valida_campos_requeridos(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_valida_formato_email(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_retorna_token_sanctum_valido(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $token = $response->json('token');

        // Verificar que el token funciona
        $authenticatedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');

        $authenticatedResponse->assertStatus(200)
            ->assertJsonFragment([
                'email' => 'test@example.com',
            ]);
    }

    public function test_login_permite_diferentes_roles(): void
    {
        $roles = ['alumno', 'tutor_egibide', 'tutor_empresa', 'admin'];

        foreach ($roles as $role) {
            User::factory()->create([
                'email' => "{$role}@example.com",
                'password' => Hash::make('password123'),
                'role' => $role,
            ]);

            $response = $this->postJson('/api/login', [
                'email' => "{$role}@example.com",
                'password' => 'password123',
            ]);

            $response->assertStatus(200)
                ->assertJsonFragment([
                    'role' => $role,
                ]);
        }
    }
}