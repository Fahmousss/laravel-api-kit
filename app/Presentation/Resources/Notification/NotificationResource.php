<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Notification;

use App\Domain\Notification\Entities\Notification;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Notification */
final class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type->value,
            'ticket_id'  => $this->ticketId,
            'payload'    => $this->payload,
            'read'       => $this->read,
            'created_at' => $this->createdAt,
        ];
    }
}
