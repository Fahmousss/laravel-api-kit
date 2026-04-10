<?php

declare(strict_types=1);

namespace App\Domain\Comment\Entities;

final class Comment
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $ticketId,
        public readonly string $authorId,
        public string $body,
        public bool $isInternal,
        public readonly ?string $createdAt,
    ) {}

    public function edit(string $newBody): void
    {
        $this->body = $newBody;
    }
}
