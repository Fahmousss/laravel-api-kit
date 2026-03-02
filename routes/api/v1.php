<?php

declare(strict_types=1);

use App\Presentation\Controllers\Api\V1\Auth\EmailVerificationController;
use App\Presentation\Controllers\Api\V1\Auth\LoginController;
use App\Presentation\Controllers\Api\V1\Auth\LogoutController;
use App\Presentation\Controllers\Api\V1\Auth\MeController;
use App\Presentation\Controllers\Api\V1\Auth\PasswordResetController;
use App\Presentation\Controllers\Api\V1\Auth\RegisterController;
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
});

// Password reset routes (public with rate limiting)
Route::middleware('throttle:6,1')->group(function (): void {
    Route::post('forgot-password', [PasswordResetController::class, 'forgot'])
        ->name('password.email');
    Route::post('reset-password', [PasswordResetController::class, 'reset'])
        ->name('password.reset');
});
