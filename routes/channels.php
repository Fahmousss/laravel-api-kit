<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Default user model channel
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Per-user notification channel — authorizes the authenticated user to
// subscribe only to their own private-notifications.{userId} channel.
Broadcast::channel('notifications.{userId}', function ($user, string $userId): bool {
    return (string) $user->id === $userId;
});

