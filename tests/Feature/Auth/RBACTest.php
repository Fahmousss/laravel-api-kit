<?php

declare(strict_types=1);

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Auth\Commands\AssignRole\AssignRoleCommand;
use App\Domain\Auth\Enums\Role;
use App\Domain\Auth\Enums\UserPermission;
use App\Infrastructure\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('assigns a role to a user and grants the associated permissions', function (): void {
    // 1. Arrange
    $user       = User::factory()->create();
    $commandBus = resolve(CommandBusInterface::class);

    // Initial check: User should not be able to manage users
    expect($user->can(UserPermission::ManageUsers->value))->toBeFalse();

    // 2. Act: Assign Admin Role
    $command = new AssignRoleCommand(
        userId: $user->id,
        role: Role::Admin
    );
    $commandBus->dispatch($command);

    // 3. Assert
    // Reload user relationships or fresh model if needed, but Gate resolves fresh Entity each time
    expect($user->can(UserPermission::ManageUsers->value))->toBeTrue()
        ->and($user->can(UserPermission::AssignRoles->value))->toBeTrue()
        ->and($user->can('non_existent_permission'))->toBeFalse();
});

it('hydrates multiple roles correctly and aggregates permissions', function (): void {
    $user       = User::factory()->create();
    $commandBus = resolve(CommandBusInterface::class);

    $commandBus->dispatch(new AssignRoleCommand($user->id, Role::User));
    $commandBus->dispatch(new AssignRoleCommand($user->id, Role::Manager));

    // A Manager can ViewUsers, but cannot AssignRoles
    expect($user->can(UserPermission::ViewUsers->value))->toBeTrue()
        ->and($user->can(UserPermission::AssignRoles->value))->toBeFalse();
});
