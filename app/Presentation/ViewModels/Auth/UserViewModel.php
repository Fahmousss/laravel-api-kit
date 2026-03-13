<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels\Auth;

use App\Application\Features\Auth\DTOs\UserDTO;
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

    public function __construct(UserDTO $dto)
    {
        $this->id              = $dto->id;
        $this->displayName     = $dto->name;
        $this->email           = $dto->email;
        $this->memberSince     = Date::parse($dto->createdAt)->format('d M Y');
        $this->isEmailVerified = $dto->emailVerifiedAt !== null;
    }
}
