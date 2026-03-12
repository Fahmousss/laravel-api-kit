<?php

declare(strict_types=1);

use App\Infrastructure\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

describe('Login page', function (): void {
    it('shows the login form for guests', function (): void {
        get('/login')->assertSuccessful()->assertViewIs('auth.login');
    });

    it('redirects authenticated users away from login', function (): void {
        $user = User::factory()->createOne();

        actingAs($user)->get('/login')->assertRedirect();
    });
});

describe('Login submission', function (): void {
    it('authenticates and redirects to dashboard with valid credentials', function (): void {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        post('/login', [
            'email'    => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('web.dashboard'));

        assertAuthenticated();
    });

    it('redirects back with errors on invalid credentials', function (): void {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        post('/login', [
            'email'    => $user->email,
            'password' => 'wrongpassword',
        ])->assertRedirect()->assertSessionHasErrors('email');

        assertGuest();
    });

    it('fails validation with missing fields', function (): void {
        post('/login', [])->assertSessionHasErrors(['email', 'password']);
    });
});

describe('Logout', function (): void {
    it('logs out the authenticated user and redirects to login', function (): void {
        $user = User::factory()->createOne();

        actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('web.login'));

        assertGuest();
    });
});

describe('Dashboard', function (): void {
    it('redirects guests to login', function (): void {
        get('/dashboard')->assertRedirect(route('web.login'));
    });

    it('shows the dashboard to authenticated users', function (): void {
        $user = User::factory()->createOne();

        actingAs($user)->get('/dashboard')->assertSuccessful()->assertViewIs('dashboard');
    });
});
