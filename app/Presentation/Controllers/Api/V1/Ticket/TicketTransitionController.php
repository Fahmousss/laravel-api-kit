<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Ticket;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Ticket\Commands\TransitionStatus\TransitionStatusCommand;
use App\Application\Features\Ticket\DTOs\TransitionTicketStatusDTO;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Ticket\TransitionStatusRequest;
use App\Presentation\Shared\Traits\ApiResponse;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;

final class TicketTransitionController extends ApiController
{
    use ApiResponse, HasAuthenticatedUser;

    public function __construct(
        private CommandBusInterface $commandBus
    ) {}

    public function __invoke(TransitionStatusRequest $request, string $project_id, string $ticket_id): JsonResponse
    {
        $this->commandBus->dispatch(new TransitionStatusCommand(
            actor: $this->actor($request),
            dto: new TransitionTicketStatusDTO(
                ticketId: $ticket_id,
                newStatus: $request->string('status'),
                comment: $request->input('comment'),
            ),
        ));

        return $this->noContent();
    }
}
