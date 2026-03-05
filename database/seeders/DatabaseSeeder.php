<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Infrastructure\Auth\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $posyandu = \App\Infrastructure\Posyandus\Models\Posyandu::create([
            'name'     => 'Posyandu Melati',
            'district' => 'Kecamatan Sukamaju',
            'location' => 'Balai Desa Sukamaju',
            'lat'      => -6.200000,
            'lng'      => 106.816666,
        ]);

        $admin = User::factory()->create([
            'name'  => 'Admin User',
            'email' => 'admin@stunting.local',
        ]);
        $this->assignRoleToUser($admin->id, \App\Domain\Auth\Enums\Role::Admin);

        $kader = User::factory()->create([
            'name'        => 'Kader Siti',
            'email'       => 'kader@stunting.local',
            'posyandu_id' => $posyandu->id,
        ]);
        $this->assignRoleToUser($kader->id, \App\Domain\Auth\Enums\Role::Kader);

        $stakeholder = User::factory()->create([
            'name'  => 'Stakeholder Budi',
            'email' => 'stakeholder@stunting.local',
        ]);
        $this->assignRoleToUser($stakeholder->id, \App\Domain\Auth\Enums\Role::Stakeholder);
    }

    private function assignRoleToUser(int $userId, \App\Domain\Auth\Enums\Role $role): void
    {
        \Illuminate\Support\Facades\DB::table('role_user')->insert([
            'user_id'    => $userId,
            'role'       => $role->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
