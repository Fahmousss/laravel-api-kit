<?php

declare(strict_types=1);

namespace App\Application\Features\Comment\Commands\DeleteComment;

use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class DeleteCommentCommand
{
    public function __construct(
        public ActorContext $actor,
        public string $commentId,
    ) {}
}
