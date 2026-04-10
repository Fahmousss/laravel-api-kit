<?php

namespace App\Application\Features\Notification\Commands\MarkAllRead;

readonly class MarkAllReadCommand
{
    public function __construct(
        public string $userId,
    ) {}
}
