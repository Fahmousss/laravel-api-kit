<?php

declare(strict_types=1);

namespace App\Domain\Comment\Repositories;

use App\Domain\Comment\Entities\Comment;
use App\Domain\Shared\Pagination\PaginatedResult;

interface CommentRepositoryInterface
{
    public function findById(string $id): ?Comment;

    public function save(Comment $comment): Comment;

    public function delete(string $id): void;

    /**
     * @return PaginatedResult<Comment>
     */
    public function paginate(string $ticketId, int $perPage, int $page): PaginatedResult;
}
