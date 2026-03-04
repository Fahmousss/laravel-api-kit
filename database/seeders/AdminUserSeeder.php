<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Auth\Enums\Role as RoleEnum;
use App\Infrastructure\Auth\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if admin already exists
        if (User::where('email', 'admin@example.com')->exists()) {
            return;
        }

        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'id'                => Str::uuid()->toString(),
            'name'              => 'Super Admin',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
        ]);

        $admin->assignRole(RoleEnum::ADMIN->value);
    }
}
