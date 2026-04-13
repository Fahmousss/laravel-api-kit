<?php

declare(strict_types=1);

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
    Route::post('register', RegisterController::class)->name('api.v1.register');
    Route::post('login', LoginController::class)->name('api.v1.login');
});

// Protected routes with authenticated rate limiter (120/min)
Route::middleware(['auth:sanctum', 'throttle:authenticated'])->group(function (): void {
    Route::post('logout', LogoutController::class)->name('api.v1.logout');
    Route::get('me', MeController::class)->name('api.v1.me');

    // Email verification
    Route::post('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('email/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Projects (no project membership check needed to list/create)
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

    Route::prefix('/projects/{project_id}')
        ->middleware(EnsureProjectMember::class)
        ->group(function () {
            Route::post('/members', AddMemberController::class);

            // Tickets
            Route::get('/tickets', [TicketController::class, 'index']);
            Route::post('/tickets', [TicketController::class, 'store']);
            Route::get('/tickets/{ticket_id}', [TicketController::class, 'show']);
            Route::patch('/tickets/{ticket_id}', [TicketController::class, 'update']);
            Route::delete('/tickets/{ticket_id}', [TicketController::class, 'destroy']);
            Route::patch('/tickets/{ticket_id}/status', TicketTransitionController::class);

            // Comments
            Route::get('/tickets/{ticket_id}/comments', [CommentController::class, 'index']);
            Route::post('/tickets/{ticket_id}/comments', [CommentController::class, 'store']);
            Route::patch('/tickets/{ticket_id}/comments/{comment_id}', [CommentController::class, 'update']);
            Route::delete('/tickets/{ticket_id}/comments/{comment_id}', [CommentController::class, 'destroy']);
        });

    // Notifications (user-scoped, no project check)
    Route::get('/notifications', GetNotificationController::class);
    Route::post('/notifications/read-all', MarkAllReadController::class);
});

// Password reset routes (public with rate limiting)
Route::middleware('throttle:6,1')->group(function (): void {
    Route::post('forgot-password', [PasswordResetController::class, 'forgot'])
        ->name('password.email');
    Route::post('reset-password', [PasswordResetController::class, 'reset'])
        ->name('password.reset');
});
