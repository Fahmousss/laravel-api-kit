<?php

declare(strict_types=1);

namespace App\Application\Features\Comment\Commands\CreateComment;

use App\Application\Features\Comment\DTOs\CommentDTO;
use App\Application\Features\Notification\Common\Interfaces\NotificationServiceInterface;
use App\Domain\Authorization\Enums\SystemAction;
use App\Domain\Comment\Entities\Comment;
use App\Domain\Comment\Repositories\CommentRepositoryInterface;
use App\Domain\Ticket\Exceptions\TicketNotFoundException;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;

final class CreateCommentCommandHandler
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository,
        private TicketRepositoryInterface $ticketRepository,
        private NotificationServiceInterface $notificationService,
    ) {}

    public function handle(CreateCommentCommand $command): CommentDTO
    {
        $dto   = $command->dto;
        $actor = $command->actor;

        // Internal comments require the POST_INTERNAL_COMMENT capability
        if ($dto->isInternal) {
            $actor->assertCan(SystemAction::POST_INTERNAL_COMMENT);
        }

        $ticket = $this->ticketRepository->findById($dto->ticketId);
        if (! $ticket) {
            throw TicketNotFoundException::withId($dto->ticketId);
        }

        $comment = new Comment(
            id: null,
            ticketId: $dto->ticketId,
            authorId: $dto->authorId,
            body: $dto->body,
            isInternal: $dto->isInternal,
            createdAt: null,
        );

        $saved = $this->commentRepository->save($comment);

        $this->notificationService->notifyCommented($ticket, $saved);

        return CommentDTO::fromEntity($saved);
    }
}

