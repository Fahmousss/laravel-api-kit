<?php

declare(strict_types=1);

use App\Domain\Auth\Enums\Role;
use App\Infrastructure\Auth\Models\User;
use App\Infrastructure\Auth\Models\UserRole;
use App\Infrastructure\Posyandus\Models\Posyandu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Web Management', function (): void {
    it('allows an admin to view the users list', function (): void {
        $admin = User::factory()->createOne();
        UserRole::query()->create(['user_id' => $admin->id, 'role' => Role::Admin->value]);

        $kader = User::factory()->createOne(['name' => 'Siti Kader']);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.users.index')
            ->assertSee('Siti Kader');
    });

    it('allows an admin to assign a role to a user', function (): void {
        $admin = User::factory()->createOne();
        UserRole::query()->create(['user_id' => $admin->id, 'role' => Role::Admin->value]);

        $user = User::factory()->createOne();

        $this->actingAs($admin)
            ->post(route('admin.users.assignRole', ['id' => $user->id]), [
                'role' => 'kader',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role'    => Role::Kader->value,
        ]);
    });

    it('allows an admin to assign a posyandu to a user', function (): void {
        $admin = User::factory()->createOne();
        UserRole::query()->create(['user_id' => $admin->id, 'role' => Role::Admin->value]);

        $user     = User::factory()->createOne();
        $posyandu = Posyandu::query()->create([
            'name'     => 'Posyandu assign test',
            'district' => 'Somewhere',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.assignPosyandu', ['id' => $user->id]), [
                'posyandu_id' => $posyandu->id,
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id'          => $user->id,
            'posyandu_id' => $posyandu->id,
        ]);
    });
});
