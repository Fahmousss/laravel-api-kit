<?php

namespace App\Application\Features\Ticket\Commands\DeleteTicket;

use App\Domain\Ticket\Repositories\TicketRepositoryInterface;
use App\Domain\Ticket\Exceptions\TicketNotFoundException;
use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Authorization\Exceptions\UnauthorizedActionException;

class DeleteTicketCommandHandler
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
    ) {}

    public function handle(DeleteTicketCommand $command): void
    {
        $role = UserRole::from($command->actorProjectRole);

        if (! $role->canDeleteTicket()) {
            throw UnauthorizedActionException::forAction("delete ticket");
        }

        $ticket = $this->ticketRepository->findById($command->ticketId);
        if (! $ticket) {
            throw TicketNotFoundException::withId($command->ticketId);
        }

        $this->ticketRepository->delete($command->ticketId);
    }
}
