<?php

namespace App\Application\Features\Comment\Commands\CreateComment;

use App\Application\Features\Comment\DTOs\CreateCommentDTO;

readonly class CreateCommentCommand
{
    public function __construct(
        public CreateCommentDTO $dto,
    ) {}
}
