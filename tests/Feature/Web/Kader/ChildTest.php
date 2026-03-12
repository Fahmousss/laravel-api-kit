<?php

declare(strict_types=1);

use App\Domain\Auth\Enums\Role;
use App\Infrastructure\Auth\Models\User;
use App\Infrastructure\Auth\Models\UserRole;
use App\Infrastructure\Children\Models\Child;
use App\Infrastructure\Posyandus\Models\Posyandu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Kader Child Web Management', function (): void {
    it('allows a kader to view children in their posyandu', function (): void {
        $posyandu = Posyandu::query()->create(['name' => 'Posyandu Mawar', 'district' => 'Kecamatan A']);
        $kader    = User::factory()->createOne(['posyandu_id' => $posyandu->id]);
        UserRole::query()->create(['user_id' => $kader->id, 'role' => Role::Kader->value]);

        Child::query()->create([
            'posyandu_id' => $posyandu->id,
            'name'        => 'Anak Kader',
            'nik'         => '1234123412341234',
            'gender'      => 'P',
            'dob'         => now()->subYears(1),
            'parent_name' => 'Ibu Kader',
        ]);

        $this->actingAs($kader)
            ->get(route('kader.children.index'))
            ->assertSuccessful()
            ->assertViewIs('kader.children.index')
            ->assertSee('Anak Kader');
    });
});
