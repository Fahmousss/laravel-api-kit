<?php

declare(strict_types=1);

use App\Presentation\Controllers\Api\V1\Auth\LoginController;
use App\Presentation\Controllers\Api\V1\Auth\LogoutController;
use App\Presentation\Controllers\Api\V1\Auth\MeController;
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
    Route::post('login', LoginController::class)->name('api.v1.login');
});

// Protected routes with authenticated rate limiter (120/min)
Route::middleware(['auth:api', 'throttle:authenticated'])->group(function (): void {
    Route::post('logout', LogoutController::class)->name('api.v1.logout');
    Route::get('me', MeController::class)->name('api.v1.me');
});
