<?php

declare(strict_types=1);

use App\Presentation\Controllers\Web\Admin\ChildController;
use App\Presentation\Controllers\Web\Admin\MeasurementController;
use App\Presentation\Controllers\Web\Admin\PosyanduController;
use App\Presentation\Controllers\Web\Admin\UserController;
use App\Presentation\Controllers\Web\Auth\LoginController;
use App\Presentation\Controllers\Web\Auth\LogoutController;
use App\Presentation\Controllers\Web\DashboardController;
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
    Route::get('/login', [LoginController::class, 'show'])->name('web.login');
    Route::post('/login', [LoginController::class, 'store'])->name('web.login.store');
});

// Public map landing
Route::get('/', App\Presentation\Controllers\Web\PublicMapController::class)->name('web.home');

// Authenticated web routes
Route::middleware('auth')->group(function (): void {
    Route::post('/logout', LogoutController::class)->name('web.logout');
    Route::get('/dashboard', DashboardController::class)->name('web.dashboard');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/', App\Presentation\Controllers\Web\Admin\DashboardController::class)->name('dashboard');

        Route::get('/posyandus', [PosyanduController::class, 'index'])->name('posyandus.index');
        Route::post('/posyandus', [PosyanduController::class, 'store'])->name('posyandus.store');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/role', [UserController::class, 'assignRole'])->name('users.assignRole');
        Route::delete('/users/{id}/role', [UserController::class, 'revokeRole'])->name('users.revokeRole');
        Route::post('/users/{id}/posyandu', [UserController::class, 'assignPosyandu'])->name('users.assignPosyandu');

        Route::get('/children', [ChildController::class, 'index'])->name('children.index');

        Route::get('/measurements', [MeasurementController::class, 'index'])->name('measurements.index');
    });

    // Kader Routes
    Route::middleware('role:kader')->prefix('kader')->name('kader.')->group(function (): void {
        Route::get('/', App\Presentation\Controllers\Web\Kader\DashboardController::class)->name('dashboard');

        Route::get('/children', [App\Presentation\Controllers\Web\Kader\ChildController::class, 'index'])->name('children.index');
        Route::post('/children', [App\Presentation\Controllers\Web\Kader\ChildController::class, 'store'])->name('children.store');

        Route::get('/measurements/create', [App\Presentation\Controllers\Web\Kader\MeasurementController::class, 'create'])->name('measurements.create');
        Route::post('/measurements', [App\Presentation\Controllers\Web\Kader\MeasurementController::class, 'store'])->name('measurements.store');
    });
});
