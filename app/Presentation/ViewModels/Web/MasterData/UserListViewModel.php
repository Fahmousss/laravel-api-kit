<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels\Web\MasterData;

use App\Application\Features\Auth\DTOs\UserDTO;
use App\Domain\Auth\Enums\Role;
use App\Domain\Shared\Pagination\PaginatedResult;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

final readonly class UserListViewModel
{
    /**
     * @var UserViewModel[]
     */
    public array $users;

    public LengthAwarePaginator $paginator;

    public function __construct(PaginatedResult $paginatedResult)
    {
        $this->users = array_map(
            static fn (UserDTO $dto): UserViewModel => new UserViewModel($dto),
            $paginatedResult->items
        );

        $this->paginator = new LengthAwarePaginator(
            items: $this->users,
            total: $paginatedResult->total,
            perPage: $paginatedResult->perPage,
            currentPage: $paginatedResult->currentPage,
            options: ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }
}

final readonly class UserViewModel
{
    public int $id;

    public string $name;

    public string $email;

    public string $rolesDisplay;

    public string $createdAtFormatted;

    /**
     * @var Role[]
     */
    public array $rawRoles;

    public function __construct(UserDTO $dto)
    {
        $this->id           = $dto->id;
        $this->name         = $dto->name;
        $this->email        = $dto->email;
        $this->rolesDisplay = $dto->roles === []
            ? 'User'
            : collect($dto->roles)->map(fn ($r) => Str::headline($r->value))->implode(', ');

        $this->rawRoles = $dto->roles;

        $this->createdAtFormatted = Date::parse($dto->createdAt)->translatedFormat('d M Y');
    }
}
