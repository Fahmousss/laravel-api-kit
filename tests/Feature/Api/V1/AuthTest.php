<?php

declare(strict_types=1);

use App\Application\Features\Auth\Common\Interfaces\AuthTokenServiceInterface;
use App\Infrastructure\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeader;

uses(RefreshDatabase::class);

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
        $token = resolve(AuthTokenServiceInterface::class)->generateForUser($user->id);

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
        $token = resolve(AuthTokenServiceInterface::class)->generateForUser($user->id);

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
