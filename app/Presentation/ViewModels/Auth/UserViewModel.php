<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels\Auth;

use App\Application\Features\Auth\DTOs\UserDTO;
use App\Domain\Auth\Enums\Role;
use Illuminate\Support\Facades\Date;

/**
 * View model for presenting authenticated user data in Blade views.
 * Accepts a UserDTO from the Application layer — no Eloquent dependency.
 */
final readonly class UserViewModel
{
    public int $id;

    public string $displayName;

    public string $email;

    public string $memberSince;

    public bool $isEmailVerified;

    public string $primaryRole;

    /**
     * @var Role[]
     */
    public array $rawRoles;

    public function __construct(UserDTO $dto)
    {
        $this->id              = $dto->id;
        $this->displayName     = $dto->name;
        $this->email           = $dto->email;
        $this->memberSince     = Date::parse($dto->createdAt)->format('d M Y');
        $this->isEmailVerified = $dto->emailVerifiedAt !== null;
        $this->primaryRole     = $this->resolvePrimaryRole($dto->roles);
        $this->rawRoles        = $dto->roles;
    }

    /**
     * @param Role[] $roles
     */
    private function resolvePrimaryRole(array $roles): string
    {
        $priority = [Role::Admin, Role::Manager, Role::Kader, Role::Stakeholder, Role::User];

        foreach ($priority as $role) {
            if (in_array($role, $roles, true)) {
                return ucfirst($role->value);
            }
        }

        return 'Member';
    }
}
