<?php

declare(strict_types=1);

namespace App\Infrastructure\Web\Providers;

use App\Presentation\ViewComposers\AuthenticatedUserComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

final class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(
            ['layouts.app', 'components.layout.topbar', 'dashboard'],
            AuthenticatedUserComposer::class
        );
    }
}
