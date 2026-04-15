<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\TransitionStatus;

use App\Application\Features\Notification\Common\Interfaces\NotificationServiceInterface;
use App\Application\Features\Ticket\Common\Interfaces\TicketActivityServiceInterface;
use App\Domain\ActivityLog\Enums\ActivityType;
use App\Domain\Authorization\Enums\SystemAction;
use App\Domain\Ticket\Enums\TicketStatus;
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
        $dto   = $command->dto;
        $actor = $command->actor;

        $ticket = $this->ticketRepository->findById($dto->ticketId);
        if (! $ticket) {
            throw TicketNotFoundException::withId($dto->ticketId);
        }

        $newStatus = $dto->newStatus;
        // Guard: closing requires canClose, reviewing requires canReview
        if (in_array($newStatus,
            [TicketStatus::CLOSED, TicketStatus::WONTFIX, TicketStatus::DUPLICATE])
        ) {
            $actor->assertCan(SystemAction::CLOSE);
        }

        if ($newStatus === TicketStatus::RESOLVED) {
            $actor->assertCan(SystemAction::REVIEW);
        }

        if (! $actor->canTransitionStatus()) {
            $actor->assertCan(SystemAction::TRANSITION_STATUS);
        }

        $previousStatus = $ticket->status;

        $ticket->transitionTo($newStatus);

        $this->ticketRepository->save($ticket);

        $this->activityService->log($ticket->id, $actor->userId, ActivityType::STATUS_CHANGED, [
            'from' => $previousStatus->value,
            'to'   => $newStatus->value,
        ]);

        $this->notificationService->notifyStatusChanged($ticket, $previousStatus);
    }
}
