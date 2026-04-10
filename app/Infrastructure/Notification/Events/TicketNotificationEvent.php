<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification\Events;

use App\Domain\Notification\Entities\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class TicketNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Notification $notification,
    ) {}

    public function broadcastOn(): array
    {
        // Private channel per user — frontend subscribes to private-notifications.{userId}
        return [
            new PrivateChannel("notifications.{$this->notification->userId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ticket.notification';
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->notification->id,
            'type'       => $this->notification->type->value,
            'ticket_id'  => $this->notification->ticketId,
            'payload'    => $this->notification->payload,
            'read'       => $this->notification->read,
            'created_at' => $this->notification->createdAt,
        ];
    }
}
