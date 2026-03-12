<?php

declare(strict_types=1);

use App\Domain\Auth\Enums\Role;
use App\Infrastructure\Auth\Models\User;
use App\Infrastructure\Auth\Models\UserRole;
use App\Infrastructure\Children\Models\Child;
use App\Infrastructure\Posyandus\Models\Posyandu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Child Web Management', function (): void {
    it('allows an admin to view the children list', function (): void {
        $admin = User::factory()->createOne();
        UserRole::query()->create(['user_id' => $admin->id, 'role' => Role::Admin->value]);

        $child = Child::query()->create([
            'posyandu_id' => Posyandu::query()->create(['name' => 'Posyandu Test', 'district' => 'Somewhere'])->id,
            'name'        => 'Budi Santoso',
            'nik'         => '1234567890123456',
            'gender'      => 'L',
            'dob'         => now()->subYears(2),
            'parent_name' => 'Siti Bapak',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.children.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.children.index')
            ->assertSee('Budi Santoso');
    });
});
