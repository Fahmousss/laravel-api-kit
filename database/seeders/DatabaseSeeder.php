<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Infrastructure\Auth\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->createOne([
            'name' => 'Test User',
            'email' => 'test@mail.com',
            'password' => 'password',
        ]);
    }
}
