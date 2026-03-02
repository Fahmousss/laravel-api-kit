<?php

declare(strict_types=1);

namespace App\Application\Bus;

use App\Application\Contracts\CommandBusInterface;
use Illuminate\Contracts\Container\Container;
use RuntimeException;

final class CommandBus implements CommandBusInterface
{
    /**
     * @var array<class-string, class-string>
     */
    private array $handlers = [];

    public function __construct(private readonly Container $container) {}

    /**
     * Register a handler class for a command class.
     *
     * @param class-string $command
     * @param class-string $handler
     */
    public function register(string $command, string $handler): void
    {
        $this->handlers[$command] = $handler;
    }

    public function dispatch(object $command): mixed
    {
        $commandClass = $command::class;

        throw_unless(isset($this->handlers[$commandClass]), RuntimeException::class, sprintf('No handler registered for command [%s].', $commandClass));

        $handler = $this->container->make($this->handlers[$commandClass]);

        return $handler->handle($command);
    }
}
