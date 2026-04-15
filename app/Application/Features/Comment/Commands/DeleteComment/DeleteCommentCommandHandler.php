<?php

declare(strict_types=1);

namespace App\Application\Features\Comment\Commands\DeleteComment;

use App\Domain\Authorization\Enums\SystemAction;
use App\Domain\Authorization\Exceptions\UnauthorizedActionException;
use App\Domain\Comment\Exceptions\CommentNotFoundException;
use App\Domain\Comment\Repositories\CommentRepositoryInterface;

final class DeleteCommentCommandHandler
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository,
    ) {}

    public function handle(DeleteCommentCommand $command): void
    {
        $comment = $this->commentRepository->findById($command->commentId);
        if (! $comment) {
            throw CommentNotFoundException::withId($command->commentId);
        }

        $isAuthor  = $comment->authorId === $command->actor->userId;
        $isManager = $command->actor->canManageMembers();

        if (! $isAuthor && ! $isManager) {
            throw UnauthorizedActionException::forAction(SystemAction::MANAGE_MEMBERS);
        }

        $this->commentRepository->delete($command->commentId);
    }
}
