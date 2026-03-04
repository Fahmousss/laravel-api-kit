<?php

declare(strict_types=1);

use App\Infrastructure\Auth\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\seed;
use function Pest\Laravel\withHeader;

uses(RefreshDatabase::class);

describe('Registration', function (): void {
    beforeEach(function (): void {
        seed(RoleAndPermissionSeeder::class);
    });

    it('registers a new user successfully', function (): void {
        $response = postJson('/api/v1/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'cadre',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'roles'],
                    'token',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'User registered successfully. Please check your email to verify your account.',
            ]);

        assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    });

    it('assigns the given role on registration', function (): void {
        postJson('/api/v1/register', [
            'name'                  => 'Cadre User',
            'email'                 => 'cadre@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'cadre',
        ]);

        $user = User::query()->where('email', 'cadre@example.com')->firstOrFail();

        expect($user->hasRole('cadre'))->toBeTrue();
    });

    it('fails registration without a role', function (): void {
        $response = postJson('/api/v1/register', [
            'name'                  => 'No Role User',
            'email'                 => 'norole@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
    });

    it('fails registration with an invalid role', function (): void {
        $response = postJson('/api/v1/register', [
            'name'                  => 'Bad Role User',
            'email'                 => 'badrole@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'superuser',
        ]);

        $response->assertStatus(422);
    });

    it('fails registration with invalid data', function (): void {
        $response = postJson('/api/v1/register', [
            'name'     => '',
            'email'    => 'invalid-email',
            'password' => 'short',
        ]);

        $response->assertStatus(422);
    });

    it('fails registration with duplicate email', function (): void {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = postJson('/api/v1/register', [
            'name'                  => 'Test User',
            'email'                 => 'existing@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'cadre',
        ]);

        $response->assertStatus(422);
    });
});

describe('Login', function (): void {
    it('logs in with valid credentials', function (): void {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = postJson('/api/v1/login', [
            'email'    => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Login successful',
            ]);
    });

    it('fails login with invalid credentials', function (): void {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = postJson('/api/v1/login', [
            'email'    => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials',
            ]);
    });

    it('fails login with non-existent user', function (): void {
        $response = postJson('/api/v1/login', [
            'email'    => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401);
    });
});

describe('Logout', function (): void {
    it('logs out authenticated user', function (): void {
        $user  = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully',
            ]);
    });

    it('fails logout without authentication', function (): void {
        $response = postJson('/api/v1/logout');

        $response->assertStatus(401);
    });
});

describe('Me', function (): void {
    it('returns authenticated user data', function (): void {
        $user  = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'name', 'email'],
            ])
            ->assertJson([
                'success' => true,
                'data'    => [
                    'id'    => $user->id,
                    'email' => $user->email,
                ],
            ]);
    });

    it('fails without authentication', function (): void {
        $response = getJson('/api/v1/me');

        $response->assertStatus(401);
    });
});
