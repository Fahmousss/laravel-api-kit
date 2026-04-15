<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Comment;

use App\Application\Features\Comment\DTOs\CommentDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CommentDTO
 */
final class CommentResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var CommentDTO $dto */
        $dto = $this->resource;

        return [
            'id'          => $dto->id,
            'ticket_id'   => $dto->ticketId,
            'author_id'   => $dto->authorId,
            'body'        => $dto->body,
            'is_internal' => $dto->isInternal,
            'created_at'  => $dto->createdAt,
        ];
    }
}
