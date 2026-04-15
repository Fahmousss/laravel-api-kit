<?php

declare(strict_types=1);

namespace App\Application\Features\Comment\Commands\EditComment;

use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class EditCommentCommand
{
    public function __construct(
        public ActorContext $actor,
        public string $commentId,
        public string $body,
    ) {}
}
