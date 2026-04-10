<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\TransitionStatus;

use App\Application\Features\Notification\Common\Interfaces\NotificationServiceInterface;
use App\Application\Features\Ticket\Common\Interfaces\TicketActivityServiceInterface;
use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Authorization\Exceptions\UnauthorizedActionException;
use App\Domain\Ticket\Exceptions\TicketNotFoundException;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;

final class TransitionStatusCommandHandler
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
        private TicketActivityServiceInterface $activityService,
        private NotificationServiceInterface $notificationService,
    ) {}

    public function handle(TransitionStatusCommand $command): void
    {
        $dto = $command->dto;

        $ticket = $this->ticketRepository->findById($dto->ticketId);
        if (! $ticket) {
            throw TicketNotFoundException::withId($dto->ticketId);
        }

        $role = UserRole::from($dto->actorProjectRole);

        // Guard: closing requires canClose, reviewing requires canReview
        if (
            in_array($dto->newStatus->value, ['closed', 'wontfix', 'duplicate'])
            && ! $role->canClose()
        ) {
            throw UnauthorizedActionException::forAction('close ticket');
        }

        if ($dto->newStatus->value === 'resolved' && ! $role->canReview()) {
            throw UnauthorizedActionException::forAction('resolve ticket');
        }

        if (! $role->canTransitionStatus()) {
            throw UnauthorizedActionException::forAction('transition ticket status');
        }

        $previousStatus = $ticket->status;

        $ticket->transitionTo($dto->newStatus);

        $this->ticketRepository->save($ticket);

        $this->activityService->log($ticket->id, $dto->actorId, 'status_changed', [
            'from' => $previousStatus->value,
            'to'   => $dto->newStatus->value,
        ]);

        $this->notificationService->notifyStatusChanged($ticket, $previousStatus);
    }
}
