<?php

declare(strict_types=1);

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API routes for your application.
| Routes are organized into versioned files located in routes/api/*.php
|
*/

// Version 1
Route::prefix('v1')->group(base_path('routes/api/v1.php'));

// Documentation
Scramble::registerUiRoute('docs/v1', api: 'v1');
Scramble::registerJsonSpecificationRoute('docs/v1/api.json', api: 'v1');

/*
 * To add a new API version (e.g., v2):
 *
 * 1. Create the corresponding route file in routes/api/v2.php
 * 2. Register the routes in this file:
 *    Route::prefix('v2')->group(base_path('routes/api/v2.php'));
 * 3. Register the documentation routes:
 *    Scramble::registerUiRoute('docs/v2', api: 'v2');
 *    Scramble::registerJsonSpecificationRoute('docs/v2/api.json', api: 'v2');
 */
