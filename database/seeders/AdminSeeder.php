<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Authorization\Enums\SystemRole;
use App\Infrastructure\Authentication\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = config('app.admin_email', 'admin@example.com');
        $adminPassword = config('app.admin_password', 'password');

        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'System Administrator',
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'system_role' => SystemRole::SYSTEM_ADMIN,
                'email_verified_at' => now(),
            ]);
        }
    }
}
