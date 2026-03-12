<?php

declare(strict_types=1);

use App\Domain\Auth\Enums\Role;
use App\Infrastructure\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

describe('RoleMiddleware RBAC on Web Routes', function (): void {
    it('allows access to admin routes for users with admin role', function (): void {
        $user = User::factory()->createOne();
        DB::table('role_user')->insert(['user_id' => $user->id, 'role' => Role::Admin->value]);

        $this->withoutExceptionHandling();
        actingAs($user)->get('/admin')->assertSuccessful();
    });

    it('denies access to admin routes for users with kader role', function (): void {
        $user = User::factory()->createOne();
        DB::table('role_user')->insert(['user_id' => $user->id, 'role' => Role::Kader->value]);

        actingAs($user)->get('/admin')->assertForbidden();
    });

    it('allows access to kader routes for users with kader role', function (): void {
        $user = User::factory()->createOne();
        DB::table('role_user')->insert(['user_id' => $user->id, 'role' => Role::Kader->value]);

        actingAs($user)->get('/kader')->assertSuccessful();
    });

    it('denies access to kader routes for users with admin role', function (): void {
        $user = User::factory()->createOne();
        DB::table('role_user')->insert(['user_id' => $user->id, 'role' => Role::Admin->value]);

        actingAs($user)->get('/kader')->assertForbidden();
    });

    it('denies access if unauthenticated', function (): void {
        get('/admin')->assertRedirect(route('web.login')); // Handled by auth middleware first
    });
});
