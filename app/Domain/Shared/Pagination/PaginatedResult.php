<?php

declare(strict_types=1);

namespace App\Domain\Shared\Pagination;

final readonly class PaginatedResult
{
    /**
     * @param array<int, mixed> $items
     */
    public function __construct(
        public array $items,
        public int $total,
        public int $perPage,
        public int $currentPage,
        public int $lastPage,
    ) {}
}
