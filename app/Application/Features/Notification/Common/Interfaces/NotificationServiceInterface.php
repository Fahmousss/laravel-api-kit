<?php

namespace App\Application\Features\Notification\Common\Interfaces;

use App\Domain\Ticket\Entities\Ticket;
use App\Domain\Comment\Entities\Comment;
use App\Domain\Shared\Enums\TicketStatus;

interface NotificationServiceInterface
{
    public function notifyTicketCreated(Ticket $ticket): void;
    public function notifyStatusChanged(Ticket $ticket, TicketStatus $previousStatus): void;
    public function notifyCommented(Ticket $ticket, Comment $comment): void;
    public function notifyAssigned(Ticket $ticket, string $assigneeId): void;
}
