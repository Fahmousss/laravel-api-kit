<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\UpdateTicket;

use App\Application\Features\Ticket\Common\Interfaces\TicketActivityServiceInterface;
use App\Domain\Ticket\Exceptions\TicketNotFoundException;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;

final class UpdateTicketCommandHandler
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
        private TicketActivityServiceInterface $activityService,
    ) {}

    public function handle(UpdateTicketCommand $command): void
    {
        $dto = $command->dto;

        $ticket = $this->ticketRepository->findById($dto->ticketId);
        if (! $ticket) {
            throw TicketNotFoundException::withId($dto->ticketId);
        }

        if ($dto->title !== null) {
            $ticket->title = $dto->title;
        }
        if ($dto->description !== null) {
            $ticket->description = $dto->description;
        }
        if ($dto->priority !== null) {
            $ticket->priority = $dto->priority;
        }
        if ($dto->assigneeId !== null) {
            $ticket->assign($dto->assigneeId);
        }
        if ($dto->dueDate !== null) {
            $ticket->dueDate = $dto->dueDate;
        }

        $this->ticketRepository->save($ticket);

        $this->activityService->log($ticket->id, $dto->actorId, 'updated', []);
    }
}
