<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Auth\Enums\Permission as PermissionEnum;
use App\Domain\Auth\Enums\Role as RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role;

final class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Admin permissions
        SpatiePermission::findOrCreate(PermissionEnum::MANAGE_USERS->value);
        SpatiePermission::findOrCreate(PermissionEnum::MANAGE_POSYANDUS->value);
        SpatiePermission::findOrCreate(PermissionEnum::VIEW_ALL_CHILDREN->value);
        SpatiePermission::findOrCreate(PermissionEnum::MANAGE_ALL_CHILDREN->value);

        // Cadre permissions
        SpatiePermission::findOrCreate(PermissionEnum::VIEW_POSYANDU_CHILDREN->value);
        SpatiePermission::findOrCreate(PermissionEnum::MANAGE_POSYANDU_CHILDREN->value);

        // Stakeholder permissions
        SpatiePermission::findOrCreate(PermissionEnum::VIEW_CLUSTERS->value);
        SpatiePermission::findOrCreate(PermissionEnum::VIEW_DASHBOARD_STATS->value);

        $admin = Role::findOrCreate(RoleEnum::ADMIN->value);
        $admin->givePermissionTo([
            PermissionEnum::MANAGE_USERS->value,
            PermissionEnum::MANAGE_POSYANDUS->value,
            PermissionEnum::VIEW_ALL_CHILDREN->value,
            PermissionEnum::MANAGE_ALL_CHILDREN->value,
        ]);

        $cadre = Role::findOrCreate(RoleEnum::CADRE->value);
        $cadre->givePermissionTo([
            PermissionEnum::VIEW_POSYANDU_CHILDREN->value,
            PermissionEnum::MANAGE_POSYANDU_CHILDREN->value,
        ]);

        $stakeholder = Role::findOrCreate(RoleEnum::STAKEHOLDER->value);
        $stakeholder->givePermissionTo([
            PermissionEnum::VIEW_CLUSTERS->value,
            PermissionEnum::VIEW_DASHBOARD_STATS->value,
        ]);
    }
}
