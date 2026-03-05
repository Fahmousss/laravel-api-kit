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

test('kader can create a child', function () {
    $posyandu = Posyandu::create([
        'name'     => 'Posyandu Test',
        'district' => 'District A',
    ]);

    /** @var Authenticatable $kader */
    $kader = User::factory()->create(['posyandu_id' => $posyandu->id]);
    DB::table('role_user')->insert(['user_id' => $kader->id, 'role' => Role::Kader->value, 'created_at' => now(), 'updated_at' => now()]);

    $response = actingAs($kader)->postJson('/api/v1/kader/children', [
        'posyandu_id' => $posyandu->id,
        'nik'         => '1234567890123456',
        'name'        => 'Anak Budi',
        'dob'         => '2024-01-01',
        'gender'      => 'L',
        'parent_name' => 'Ibu Budi',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Anak Budi');

    assertDatabaseHas('children', [
        'nik'  => '1234567890123456',
        'name' => 'Anak Budi',
    ]);
});
