<?php

declare(strict_types=1);

namespace App\Application\Features\Comment\Queries\ListComments;

use App\Application\Features\Comment\DTOs\CommentDTO;
use App\Domain\Comment\Entities\Comment;
use App\Domain\Comment\Repositories\CommentRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

final class ListCommentsQueryHandler
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository,
    ) {}

    public function handle(ListCommentsQuery $query): PaginatedResult
    {
        $includeInternal = $query->actor->canPostInternalComment();

        $result = $this->commentRepository->paginate(
            ticketId: $query->ticketId,
            perPage: $query->perPage,
            page: $query->page,
            includeInternal: $includeInternal,
        );

        return new PaginatedResult(
            items: array_map(
                fn (Comment $comment): CommentDTO => CommentDTO::fromEntity($comment),
                $result->items,
            ),
            total: $result->total,
            perPage: $result->perPage,
            currentPage: $result->currentPage,
            lastPage: $result->lastPage,
        );
    }
}
