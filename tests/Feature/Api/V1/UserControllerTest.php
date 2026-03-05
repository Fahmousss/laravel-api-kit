<?php

declare(strict_types=1);

use App\Domain\Auth\Enums\Role;
use App\Infrastructure\Auth\Models\User;
use App\Infrastructure\Posyandus\Models\Posyandu;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

test('admin can assign posyandu to a user', function (): void {
    /** @var Authenticatable $admin */
    $admin = User::factory()->create();
    DB::table('role_user')->insert(['user_id' => $admin->id, 'role' => Role::Admin->value, 'created_at' => now(), 'updated_at' => now()]);

    $kader = User::factory()->create();

    $posyandu = Posyandu::query()->create([
        'name'     => 'Posyandu Indah',
        'district' => 'Kecamatan B',
    ]);

    $response = actingAs($admin)->postJson(sprintf('/api/v1/admin/users/%d/assign-posyandu', $kader->id), [
        'posyandu_id' => $posyandu->id,
    ]);

    $response->assertStatus(200);

    assertDatabaseHas('users', [
        'id'          => $kader->id,
        'posyandu_id' => $posyandu->id,
    ]);
});
