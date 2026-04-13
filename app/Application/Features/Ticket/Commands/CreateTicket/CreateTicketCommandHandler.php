<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\CreateTicket;

use App\Application\Features\Notification\Common\Interfaces\NotificationServiceInterface;
use App\Application\Features\Ticket\Common\Interfaces\TicketActivityServiceInterface;
use App\Application\Features\Ticket\DTOs\TicketDTO;
use App\Domain\ActivityLog\Enums\ActivityType;
use App\Domain\Authorization\Enums\SystemAction;
use App\Domain\Project\Exceptions\ProjectNotFoundException;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Domain\Ticket\Entities\Ticket;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;

final class CreateTicketCommandHandler
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
        private ProjectRepositoryInterface $projectRepository,
        private TicketActivityServiceInterface $activityService,
        private NotificationServiceInterface $notificationService,
    ) {}

    public function handle(CreateTicketCommand $command): TicketDTO
    {
        $dto = $command->dto;
        $actor = $command->actor;

        $actor->assertCan(SystemAction::CREATE_TICKET);

        $project = $this->projectRepository->findById($dto->projectId);
        if (! $project) {
            throw ProjectNotFoundException::withId($dto->projectId);
        }

        $ticketNumber = $this->ticketRepository->nextTicketNumber($dto->projectId);

        $ticket = new Ticket(
            id: null,
            projectId: $dto->projectId,
            reporterId: $actor->userId,
            assigneeId: $dto->assigneeId,
            ticketNumber: $ticketNumber,
            title: $dto->title,
            description: $dto->description,
            type: $dto->type,
            status: $dto->type->defaultStatus(),
            priority: $dto->priority,
            dueDate: $dto->dueDate,
            resolvedAt: null,
            createdAt: null,
            updatedAt: null,
        );

        $saved = $this->ticketRepository->save($ticket);

        $this->activityService->log($saved->id, $actor->userId, ActivityType::CREATED, [
            'title' => $saved->title,
            'type'  => $saved->type->value,
        ]);

        $this->notificationService->notifyTicketCreated($saved);

        return TicketDTO::fromEntity($saved);
    }
}

