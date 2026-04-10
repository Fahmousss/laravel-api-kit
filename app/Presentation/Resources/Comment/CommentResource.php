<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Comment;

use App\Domain\Comment\Entities\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Comment */
final class CommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'ticket_id'   => $this->ticketId,
            'author_id'   => $this->authorId,
            'body'        => $this->body,
            'is_internal' => $this->isInternal,
            'created_at'  => $this->createdAt,
        ];
    }
}
