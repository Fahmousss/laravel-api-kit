<?php

declare(strict_types=1);

namespace App\Presentation\Resources\ActivityLog;

use App\Application\Features\ActivityLog\DTOs\ActivityLogDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ActivityLogDTO
 */
final class ActivityLogResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ActivityLogDTO $dto */
        $dto = $this->resource;

        return [
            'id'         => $dto->id,
            'ticket_id'  => $dto->ticketId,
            'actor_id'   => $dto->actorId,
            'action'     => $dto->action,
            'payload'    => $dto->payload,
            'created_at' => $dto->createdAt,
        ];
    }
}
