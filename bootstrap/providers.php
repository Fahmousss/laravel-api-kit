<?php

return [
    App\Infrastructure\ActivityLog\Providers\ActivityLogServiceProvider::class,
    App\Infrastructure\Authentication\Providers\AuthenticationServiceProvider::class,
    App\Infrastructure\Authorization\Providers\AuthorizationServiceProvider::class,
    App\Infrastructure\Comment\Providers\CommentServiceProvider::class,
    App\Infrastructure\Notification\Providers\NotificationServiceProvider::class,
    App\Infrastructure\Project\Providers\ProjectServiceProvider::class,
    App\Infrastructure\Shared\Providers\DocumentationServiceProvider::class,
    App\Infrastructure\Shared\Providers\RateLimitServiceProvider::class,
    App\Infrastructure\Ticket\Providers\TicketServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\HorizonServiceProvider::class,
];
