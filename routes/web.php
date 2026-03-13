<?php

declare(strict_types=1);

use App\Presentation\Controllers\Web\Auth\EmailVerificationController;
use App\Presentation\Controllers\Web\Auth\LoginController;
use App\Presentation\Controllers\Web\Auth\LogoutController;
use App\Presentation\Controllers\Web\Auth\PasswordResetController;
use App\Presentation\Controllers\Web\Auth\RegistrationController;
use App\Presentation\Controllers\Web\DashboardController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Session-based web routes. Authentication uses Laravel's built-in
| web guard (cookie/session), independent from the Sanctum API token auth.
|
*/

// Guest-only routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegistrationController::class, 'create'])->name('register');
    Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::get('/', fn (): Factory|View => view('welcome'))->name('home');

// Authenticated web routes
Route::middleware(['auth'])->group(function (): void {
    Route::post('/logout', LogoutController::class)->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Email Verification
    Route::get('/verify-email', [EmailVerificationController::class, 'show'])->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');
});
