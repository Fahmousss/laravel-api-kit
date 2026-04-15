<?php

declare(strict_types=1);

namespace App\Application\Features\Comment\Queries\ListComments;

use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class ListCommentsQuery
{
    public function __construct(
        public ActorContext $actor,
        public string $ticketId,
        public int $perPage = 20,
        public int $page = 1,
    ) {}
}
