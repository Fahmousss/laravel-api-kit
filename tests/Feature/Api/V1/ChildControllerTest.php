<?php

use App\Infrastructure\Auth\Models\User;
use App\Domain\Auth\Enums\Role;
use App\Infrastructure\Posyandus\Models\Posyandu;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\postJson;
use function Pest\Laravel\actingAs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Auth\Authenticatable;

uses(RefreshDatabase::class);

test('kader can create a child', function () {
    $posyandu = Posyandu::create([
        'name' => 'Posyandu Test',
        'district' => 'District A',
    ]);

    /** @var Authenticatable $kader */
    $kader = User::factory()->create(['posyandu_id' => $posyandu->id]);
    DB::table('role_user')->insert(['user_id' => $kader->id, 'role' => Role::Kader->value, 'created_at' => now(), 'updated_at' => now()]);

    $response = actingAs($kader)->postJson('/api/v1/children', [
        'posyandu_id' => $posyandu->id,
        'nik'         => '1234567890123456',
        'name'        => 'Anak Budi',
        'dob'         => '2024-01-01',
        'gender'      => 'L',
        'parent_name' => 'Ibu Budi',
    ]);

    $response->assertStatus(201)
             ->assertJsonPath('data.name', 'Anak Budi');
             
    $this->assertDatabaseHas('children', [
        'nik' => '1234567890123456',
        'name' => 'Anak Budi',
    ]);
});
