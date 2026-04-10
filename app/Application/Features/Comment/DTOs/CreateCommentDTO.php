<?php

namespace App\Application\Features\Comment\DTOs;

readonly class CreateCommentDTO
{
    public function __construct(
        public string $ticketId,
        public string $authorId,
        public string $body,
        public bool   $isInternal = false,
    ) {}
}
