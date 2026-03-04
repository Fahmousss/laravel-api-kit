<?php

declare(strict_types=1);

namespace App\Application\Contracts;

interface CommandBusInterface
{
    /**
     * Register a handler class for a command class.
     *
     * @param class-string $command
     * @param class-string $handler
     */
    public function register(string $command, string $handler): void;

    public function dispatch(object $command): mixed;
}
