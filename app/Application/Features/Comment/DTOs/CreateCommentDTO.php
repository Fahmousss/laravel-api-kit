<?php

declare(strict_types=1);

namespace App\Application\Features\Comment\DTOs;

final readonly class CreateCommentDTO
{
    public function __construct(
        public string $ticketId,
        public string $authorId,
        public string $body,
        public bool $isInternal = false,
    ) {}
}
