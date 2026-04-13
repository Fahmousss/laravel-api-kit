<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\DeleteTicket;

use App\Application\Features\Ticket\Common\Interfaces\TicketActivityServiceInterface;
use App\Domain\ActivityLog\Enums\ActivityType;
use App\Domain\Authorization\Enums\SystemAction;
use App\Domain\Ticket\Exceptions\TicketNotFoundException;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;

final class DeleteTicketCommandHandler
{
    public function __construct(
        private TicketRepositoryInterface     $ticketRepository,
        private TicketActivityServiceInterface $activityService,
    ) {}

    public function handle(DeleteTicketCommand $command): void
    {
        $actor = $command->actor;

        $actor->assertCan(SystemAction::DELETE_TICKET);

        $ticket = $this->ticketRepository->findById($command->ticketId);
        if (! $ticket) {
            throw TicketNotFoundException::withId($command->ticketId);
        }

        // Log before deletion so the ticket_id FK is still valid
        $this->activityService->log($ticket->id, $actor->userId, ActivityType::DELETED, [
            'title' => $ticket->title,
            'type'  => $ticket->type->value,
        ]);

        $this->ticketRepository->delete($command->ticketId);
    }
}

