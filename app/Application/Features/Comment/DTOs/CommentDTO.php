<?php

declare(strict_types=1);

namespace App\Application\Features\Comment\DTOs;

use App\Domain\Comment\Entities\Comment;

final readonly class CommentDTO
{
    public function __construct(
        public string $id,
        public string $ticketId,
        public string $authorId,
        public string $body,
        public bool $isInternal,
        public string $createdAt,
    ) {}

    public static function fromEntity(Comment $comment): self
    {
        return new self(
            id: $comment->id,
            ticketId: $comment->ticketId,
            authorId: $comment->authorId,
            body: $comment->body,
            isInternal: $comment->isInternal,
            createdAt: $comment->createdAt ?? now()->toIso8601String(),
        );
    }
}
