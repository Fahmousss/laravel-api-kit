<?php

declare(strict_types=1);

namespace App\Application\Features\Notification\Commands\MarkAllRead;

use App\Domain\Notification\Repositories\NotificationRepositoryInterface;

final class MarkAllReadCommandHandler
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository,
    ) {}

    public function handle(MarkAllReadCommand $command): void
    {
        $this->notificationRepository->markAllReadForUser($command->userId);
    }
}
