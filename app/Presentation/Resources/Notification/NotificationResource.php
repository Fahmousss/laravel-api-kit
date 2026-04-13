<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Notification;

use App\Application\Features\Notification\DTOs\NotificationDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin NotificationDTO */
final class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var NotificationDTO $dto */
        $dto = $this->resource;

        return [
            'id'         => $dto->id,
            'type'       => $dto->type->value,
            'ticket_id'  => $dto->ticketId,
            'payload'    => $dto->payload,
            'read'       => $dto->read,
            'created_at' => $dto->createdAt,
        ];
    }
}

