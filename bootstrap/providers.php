<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Infrastructure\Authentication\Providers\AuthenticationServiceProvider::class,
    App\Infrastructure\Authorization\Providers\AuthorizationServiceProvider::class,
    App\Infrastructure\Shared\Providers\RateLimitServiceProvider::class,
    App\Infrastructure\Shared\Providers\DocumentationServiceProvider::class,
    App\Infrastructure\Project\Providers\ProjectServiceProvider::class,
    App\Infrastructure\Ticket\Providers\TicketServiceProvider::class,
    App\Infrastructure\ActivityLog\Providers\ActivityLogServiceProvider::class,
    App\Infrastructure\Comment\Providers\CommentServiceProvider::class,
    App\Infrastructure\Notification\Providers\NotificationServiceProvider::class,
];


