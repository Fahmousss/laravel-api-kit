<?php

declare(strict_types=1);

namespace App\Application\Features\Notification\Commands\MarkAllRead;

final readonly class MarkAllReadCommand
{
    public function __construct(
        public string $userId,
    ) {}
}
