<?php

declare(strict_types=1);

use App\Domain\Auth\Enums\Role;
use App\Infrastructure\Auth\Models\User;
use App\Infrastructure\Auth\Models\UserRole;
use App\Infrastructure\Children\Models\Child;
use App\Infrastructure\Posyandus\Models\Posyandu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Kader Measurement Web Management', function (): void {
    it('allows a kader to access the measurement form', function (): void {
        $posyandu = Posyandu::query()->create(['name' => 'Posyandu Kader', 'district' => 'Somewhere']);
        $kader    = User::factory()->createOne(['posyandu_id' => $posyandu->id]);
        UserRole::query()->create(['user_id' => $kader->id, 'role' => Role::Kader->value]);

        $child = Child::query()->create([
            'posyandu_id' => $posyandu->id,
            'name'        => 'Balita Diukur',
            'nik'         => '0987654321123456',
            'gender'      => 'L',
            'dob'         => now()->subMonths(6),
            'parent_name' => 'Bapak Budi',
        ]);

        $this->actingAs($kader)
            ->get(route('kader.measurements.create', ['child_id' => $child->id]))
            ->assertSuccessful()
            ->assertViewIs('kader.measurements.create')
            ->assertSee('Balita Diukur');
    });
});
