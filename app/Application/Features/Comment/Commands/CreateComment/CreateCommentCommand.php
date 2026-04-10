<?php

declare(strict_types=1);

namespace App\Application\Features\Comment\Commands\CreateComment;

use App\Application\Features\Comment\DTOs\CreateCommentDTO;

final readonly class CreateCommentCommand
{
    public function __construct(
        public CreateCommentDTO $dto,
    ) {}
}
