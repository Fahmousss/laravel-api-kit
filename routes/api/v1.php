<?php

declare(strict_types=1);

use App\Domain\Shared\Enums\RouteName;
use App\Presentation\Controllers\Api\V1\ActivityLog\ActivityLogController;
use App\Presentation\Controllers\Api\V1\Auth\EmailVerificationController;
use App\Presentation\Controllers\Api\V1\Auth\LoginController;
use App\Presentation\Controllers\Api\V1\Auth\LogoutController;
use App\Presentation\Controllers\Api\V1\Auth\MeController;
use App\Presentation\Controllers\Api\V1\Auth\PasswordResetController;
use App\Presentation\Controllers\Api\V1\Auth\RegisterController;
use App\Presentation\Controllers\Api\V1\Comment\CommentController;
use App\Presentation\Controllers\Api\V1\Notification\GetNotificationController;
use App\Presentation\Controllers\Api\V1\Notification\MarkAllReadController;
use App\Presentation\Controllers\Api\V1\Project\AddMemberController;
use App\Presentation\Controllers\Api\V1\Project\ProjectController;
use App\Presentation\Controllers\Api\V1\Ticket\TicketController;
use App\Presentation\Controllers\Api\V1\Ticket\TicketTransitionController;
use App\Presentation\Middleware\EnsureProjectMember;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Routes for API version 1.
|
*/

// Public routes with auth rate limiter (5/min - brute force protection)
Route::middleware('throttle:auth')->group(function (): void {
    Route::post('register', RegisterController::class)->name(RouteName::REGISTER);
    Route::post('login', LoginController::class)->name(RouteName::LOGIN);
});

// Protected routes with authenticated rate limiter (120/min)
Route::middleware(['auth:sanctum', 'throttle:authenticated'])->group(function (): void {
    Route::post('logout', LogoutController::class)->name(RouteName::LOGOUT);
    Route::get('me', MeController::class)->name(RouteName::ME);

    // Email verification
    Route::post('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')
        ->name(RouteName::EMAIL_VERIFY);
    Route::post('email/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name(RouteName::EMAIL_RESEND);

    // Projects (no project membership check needed to list/create)
    Route::get('/projects', [ProjectController::class, 'index'])->name(RouteName::PROJECTS_INDEX);
    Route::post('/projects', [ProjectController::class, 'store'])->name(RouteName::PROJECTS_STORE);

    Route::prefix('/projects/{project_id}')
        ->middleware(EnsureProjectMember::class)
        ->group(function () {
            Route::post('/members', AddMemberController::class)->name(RouteName::PROJECTS_ADD_MEMBER);

            // Tickets
            Route::get('/tickets', [TicketController::class, 'index'])->name(RouteName::TICKETS_INDEX);
            Route::post('/tickets', [TicketController::class, 'store'])->name(RouteName::TICKETS_STORE);
            Route::get('/tickets/{ticket_id}', [TicketController::class, 'show'])->name(RouteName::TICKETS_SHOW);
            Route::patch('/tickets/{ticket_id}', [TicketController::class, 'update'])->name(RouteName::TICKETS_UPDATE);
            Route::delete('/tickets/{ticket_id}', [TicketController::class, 'destroy'])->name(RouteName::TICKETS_DESTROY);
            Route::patch('/tickets/{ticket_id}/status', TicketTransitionController::class)->name(RouteName::TICKETS_TRANSITION);

            // Comments
            Route::get('/tickets/{ticket_id}/comments', [CommentController::class, 'index'])->name(RouteName::COMMENTS_INDEX);
            Route::post('/tickets/{ticket_id}/comments', [CommentController::class, 'store'])->name(RouteName::COMMENTS_STORE);
            Route::patch('/tickets/{ticket_id}/comments/{comment_id}', [CommentController::class, 'update'])->name(RouteName::COMMENTS_UPDATE);
            Route::delete('/tickets/{ticket_id}/comments/{comment_id}', [CommentController::class, 'destroy'])->name(RouteName::COMMENTS_DESTROY);

            // Activity Log
            Route::get('/tickets/{ticket_id}/activity', ActivityLogController::class)->name(RouteName::ACTIVITY_LOG_INDEX);
        });

    // Notifications (user-scoped, no project check)
    Route::get('/notifications', GetNotificationController::class)->name(RouteName::NOTIFICATIONS_INDEX);
    Route::post('/notifications/read-all', MarkAllReadController::class)->name(RouteName::NOTIFICATIONS_MARK_ALL_READ);
});

// Password reset routes (public with rate limiting)
Route::middleware('throttle:6,1')->group(function (): void {
    Route::post('forgot-password', [PasswordResetController::class, 'forgot'])
        ->name(RouteName::PASSWORD_EMAIL);
    Route::post('reset-password', [PasswordResetController::class, 'reset'])
        ->name(RouteName::PASSWORD_RESET);
});
