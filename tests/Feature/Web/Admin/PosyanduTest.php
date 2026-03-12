<?php

declare(strict_types=1);

use App\Domain\Auth\Enums\Role;
use App\Infrastructure\Auth\Models\User;
use App\Infrastructure\Auth\Models\UserRole;
use App\Infrastructure\Posyandus\Models\Posyandu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Posyandu Web Management', function (): void {
    it('allows an admin to view the posyandus list', function (): void {
        $admin = User::factory()->createOne();
        UserRole::query()->create(['user_id' => $admin->id, 'role' => Role::Admin->value]);

        $posyandu = Posyandu::query()->create([
            'name'     => 'Mawar Putih',
            'district' => 'Somewhere',
            'location' => 'Street 1',
            'lat'      => -6.0,
            'lng'      => 106.0,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.posyandus.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.posyandus.index')
            ->assertSee('Mawar Putih');
    });

    it('allows an admin to create a new posyandu', function (): void {
        $admin = User::factory()->createOne();
        UserRole::query()->create(['user_id' => $admin->id, 'role' => Role::Admin->value]);

        $payload = [
            'name'     => 'Melati Hijau',
            'district' => 'Kebayoran',
            'location' => 'Jl. Jalan 123',
            'lat'      => -6.21,
            'lng'      => 106.84,
        ];

        $this->actingAs($admin)
            ->post(route('admin.posyandus.store'), $payload)
            ->assertRedirect(route('admin.posyandus.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('posyandus', [
            'name'     => 'Melati Hijau',
            'district' => 'Kebayoran',
        ]);
    });
});
