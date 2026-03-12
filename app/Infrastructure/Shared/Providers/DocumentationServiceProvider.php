<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\ServiceProvider;

final class DocumentationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
            ->expose(false)
            ->withDocumentTransformers(function (OpenApi $openApi): void {
                $openApi->secure(
                    securityScheme: SecurityScheme::http('bearer')
                );
            });

        Scramble::registerApi('v1', [
            'api_path' => 'api/v1',
            'info'     => [
                'version'     => '1.0.0',
                'description' => 'API v1 description for Scramble',
            ],
            'ui' => [
                'title'        => 'API v1',
                'theme'        => 'system',
                'hide_try_it'  => false,
                'hide_schemas' => true,
            ],
        ]);

        /*
         * To add a new API version, register it using Scramble::registerApi.
         *
         * Example for v2:
         * Scramble::registerApi('v2', [
         *     'api_path' => 'api/v2',
         *     'info'     => [
         *         'version'     => '2.0.0',
         *         'description' => 'API v2 description for Scramble',
         *     ],
         *     'ui' => [
         *         'title'        => 'API v2',
         *         'theme'        => 'system',
         *         'hide_schemas' => true,
         *     ],
         * ]);
         */

    }
}
