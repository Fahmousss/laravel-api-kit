<?php

declare(strict_types=1);

namespace App\Presentation\ViewComposers;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\DTOs\UserDTO;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use App\Presentation\ViewModels\Auth\UserViewModel;
use Illuminate\View\View;

final readonly class AuthenticatedUserComposer
{
    use HasAuthenticatedUser;

    public function __construct(
        private QueryBusInterface $queryBus,
    ) {}

    public function compose(View $view): void
    {
        $dto = $this->getAuthenticatedUser();

        if ($dto instanceof UserDTO) {
            $view->with('authenticatedUser', new UserViewModel($dto));
        }
    }
}
