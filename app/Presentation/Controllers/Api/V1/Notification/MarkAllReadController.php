<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Notification;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Notification\Commands\MarkAllRead\MarkAllReadCommand;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Shared\Traits\ApiResponse;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MarkAllReadController extends ApiController
{
    use ApiResponse, HasAuthenticatedUser;

    public function __construct(
        private CommandBusInterface $commandBus
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->commandBus->dispatch(new MarkAllReadCommand(
            userId: $this->getAuthUserId(),
        ));

        return $this->success(message: 'All notifications marked as read.');
    }
}
