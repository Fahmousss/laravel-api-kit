<?php

declare(strict_types=1);

namespace App\Application\Features\Comment\Commands\CreateComment;

use App\Application\Features\Notification\Common\Interfaces\NotificationServiceInterface;
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

    public function handle(CreateCommentCommand $command): Comment
    {
        $dto = $command->dto;

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

        return $saved;
    }
}
