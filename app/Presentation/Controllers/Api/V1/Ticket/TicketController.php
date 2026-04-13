<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Ticket;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Ticket\Commands\CreateTicket\CreateTicketCommand;
use App\Application\Features\Ticket\Commands\DeleteTicket\DeleteTicketCommand;
use App\Application\Features\Ticket\Commands\UpdateTicket\UpdateTicketCommand;
use App\Application\Features\Ticket\DTOs\CreateTicketDTO;
use App\Application\Features\Ticket\DTOs\UpdateTicketDTO;
use App\Application\Features\Ticket\Queries\GetTicket\GetTicketQuery;
use App\Application\Features\Ticket\Queries\ListTickets\ListTicketsQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Ticket\CreateTicketRequest;
use App\Presentation\Requests\Api\V1\Ticket\UpdateTicketRequest;
use App\Presentation\Resources\Ticket\TicketResource;
use App\Presentation\Shared\Traits\ApiResponse;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class TicketController extends ApiController
{
    use ApiResponse, HasAuthenticatedUser;

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {}

    public function index(Request $request, string $project_id): JsonResponse
    {
        $result = $this->queryBus->dispatch(new ListTicketsQuery(
            projectId: $project_id,
            actorId: $this->actor($request)->userId,
            filters: $request->only(['status', 'type', 'priority', 'assignee']),
            perPage: (int) $request->input('per_page', 15),
            page: (int) $request->input('page', 1),
        ));

        return $this->paginated(
            resourceClass: TicketResource::class,
            paginatedResult: $result,
        );
    }

    public function show(Request $request, string $project_id, string $ticket_id): JsonResponse
    {
        $ticket = $this->queryBus->dispatch(new GetTicketQuery(
            ticketId: $ticket_id,
            actorId: $this->actor($request)->userId,
        ));

        return $this->success(data: new TicketResource($ticket));
    }

    public function store(CreateTicketRequest $request, string $project_id): JsonResponse
    {
        $ticket = $this->commandBus->dispatch(new CreateTicketCommand(
            actor: $this->actor($request),
            dto: new CreateTicketDTO(
                projectId: $project_id,
                title: $request->string('title'),
                description: $request->string('description'),
                type: $request->string('type'),
                priority: $request->string('priority'),
                assigneeId: $request->input('assignee_id'),
                dueDate: $request->input('due_date'),
                labelIds: $request->input('label_ids', []),
            ),
        ));

        return $this->created(data: new TicketResource($ticket));
    }

    public function update(UpdateTicketRequest $request, string $project_id, string $ticket_id): JsonResponse
    {
        $this->commandBus->dispatch(new UpdateTicketCommand(
            actor: $this->actor($request),
            dto: new UpdateTicketDTO(
                ticketId: $ticket_id,
                title: $request->input('title'),
                description: $request->input('description'),
                priority: $request->input('priority'),
                assigneeId: $request->input('assignee_id'),
                dueDate: $request->input('due_date'),
            ),
        ));

        return $this->noContent();
    }

    public function destroy(Request $request, string $project_id, string $ticket_id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteTicketCommand(
            ticketId: $ticket_id,
            actor: $this->actor($request),
        ));

        return $this->noContent();
    }
}

