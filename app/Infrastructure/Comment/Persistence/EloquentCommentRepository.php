<?php

declare(strict_types=1);

namespace App\Infrastructure\Comment\Persistence;

use App\Domain\Comment\Entities\Comment as CommentEntity;
use App\Domain\Comment\Repositories\CommentRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;
use App\Infrastructure\Comment\Models\Comment as CommentModel;
use App\Infrastructure\Shared\Traits\EntityMapper;

final class EloquentCommentRepository implements CommentRepositoryInterface
{
    use EntityMapper;

    public function findById(string $id): ?CommentEntity
    {
        $model = CommentModel::find($id);

        return $model ? $this->mapToEntity($model, CommentEntity::class) : null;
    }

    public function save(CommentEntity $comment): CommentEntity
    {
        $model = $comment->id
            ? CommentModel::findOrFail($comment->id)
            : new CommentModel();

        $model->fill([
            'ticket_id'   => $comment->ticketId,
            'author_id'   => $comment->authorId,
            'body'        => $comment->body,
            'is_internal' => $comment->isInternal,
            'created_at'  => $comment->createdAt ?? now(),
        ])->save();

        return $this->mapToEntity($model->fresh(), CommentEntity::class);
    }

    public function delete(string $id): void
    {
        CommentModel::destroy($id);
    }

    public function paginate(
        string $ticketId,
        int $perPage,
        int $page,
        bool $includeInternal = false,
    ): PaginatedResult {
        $query = CommentModel::where('ticket_id', $ticketId);

        if (! $includeInternal) {
            $query->where('is_internal', false);
        }

        $paginator = $query
            ->orderBy('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return new PaginatedResult(
            items: array_map(
                fn (CommentModel $m) => $this->mapToEntity($m, CommentEntity::class),
                $paginator->items(),
            ),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage(),
        );
    }
}
