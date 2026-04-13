<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\DeleteTicket;

use App\Domain\Authorization\Enums\SystemAction;
use App\Domain\Authorization\Exceptions\UnauthorizedActionException;
use App\Domain\Ticket\Exceptions\TicketNotFoundException;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;

final class DeleteTicketCommandHandler
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
    ) {}

    public function handle(DeleteTicketCommand $command): void
    {
        $actor = $command->actor;

        $actor->assertCan(SystemAction::DELETE_TICKET);

        $ticket = $this->ticketRepository->findById($command->ticketId);
        if (! $ticket) {
            throw TicketNotFoundException::withId($command->ticketId);
        }

        $this->ticketRepository->delete($command->ticketId);
    }
}
