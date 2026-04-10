<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification\Services;

use App\Application\Features\Notification\Common\Interfaces\NotificationServiceInterface;
use App\Domain\Comment\Entities\Comment as CommentEntity;
use App\Domain\Notification\Entities\Notification as NotificationEntity;
use App\Domain\Notification\Enums\NotificationType;
use App\Domain\Notification\Repositories\NotificationRepositoryInterface;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;
use App\Domain\Ticket\Entities\Ticket as TicketEntity;
use App\Domain\Ticket\Enums\TicketStatus;
use App\Infrastructure\Notification\Events\TicketNotificationEvent;

final class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository,
        private ProjectMemberRepositoryInterface $memberRepository,
    ) {}

    public function notifyTicketCreated(TicketEntity $ticket): void
    {
        $this->fanOut(
            type: NotificationType::TICKET_CREATED,
            ticket: $ticket,
            payload: ['ticket_id' => $ticket->id, 'title' => $ticket->title],
            exclude: [$ticket->reporterId],
        );
    }

    public function notifyStatusChanged(TicketEntity $ticket, TicketStatus $previousStatus): void
    {
        $recipients = array_filter(
            [$ticket->reporterId, $ticket->assigneeId],
            fn ($id) => $id !== null
        );

        foreach (array_unique($recipients) as $userId) {
            $this->createAndBroadcast(
                userId: $userId,
                ticketId: $ticket->id,
                type: NotificationType::TICKET_STATUS_CHANGED,
                payload: [
                    'ticket_id' => $ticket->id,
                    'from'      => $previousStatus->value,
                    'to'        => $ticket->status->value,
                ],
            );
        }
    }

    public function notifyCommented(TicketEntity $ticket, CommentEntity $comment): void
    {
        $recipients = array_filter(
            [$ticket->reporterId, $ticket->assigneeId],
            fn ($id) => $id !== null && $id !== $comment->authorId
        );

        foreach (array_unique($recipients) as $userId) {
            $this->createAndBroadcast(
                userId: $userId,
                ticketId: $ticket->id,
                type: NotificationType::TICKET_COMMENTED,
                payload: [
                    'ticket_id'  => $ticket->id,
                    'comment_id' => $comment->id,
                    'author_id'  => $comment->authorId,
                ],
            );
        }
    }

    public function notifyAssigned(TicketEntity $ticket, string $assigneeId): void
    {
        $this->createAndBroadcast(
            userId: $assigneeId,
            ticketId: $ticket->id,
            type: NotificationType::TICKET_ASSIGNED,
            payload: ['ticket_id' => $ticket->id, 'title' => $ticket->title],
        );
    }

    private function fanOut(NotificationType $type, TicketEntity $ticket, array $payload, array $exclude): void
    {
        $members = $this->memberRepository->listMembers($ticket->projectId);

        foreach ($members as $member) {
            if (in_array($member->userId, $exclude)) {
                continue;
            }

            $this->createAndBroadcast(
                userId: $member->userId,
                ticketId: $ticket->id,
                type: $type,
                payload: $payload,
            );
        }
    }

    private function createAndBroadcast(
        string $userId,
        string $ticketId,
        NotificationType $type,
        array $payload
    ): void {
        $notification = new NotificationEntity(
            id: null,
            userId: $userId,
            ticketId: $ticketId,
            type: $type,
            payload: $payload,
            read: false,
            createdAt: null,
        );

        $saved = $this->notificationRepository->save($notification);

        // Broadcast via Laravel Reverb WebSocket
        broadcast(new TicketNotificationEvent($saved))->toOthers();
    }
}
