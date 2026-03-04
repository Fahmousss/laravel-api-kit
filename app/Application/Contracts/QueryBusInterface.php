<?php

declare(strict_types=1);

namespace App\Application\Contracts;

interface QueryBusInterface
{
    /**
     * Register a handler class for a query class.
     *
     * @param class-string $query
     * @param class-string $handler
     */
    public function register(string $query, string $handler): void;

    public function dispatch(object $query): mixed;
}
