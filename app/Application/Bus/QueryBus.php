<?php

declare(strict_types=1);

namespace App\Application\Bus;

use App\Application\Contracts\QueryBusInterface;
use Illuminate\Contracts\Container\Container;
use RuntimeException;

final class QueryBus implements QueryBusInterface
{
    /**
     * @var array<class-string, class-string>
     */
    private array $handlers = [];

    public function __construct(private readonly Container $container) {}

    /**
     * Register a handler class for a query class.
     *
     * @param class-string $query
     * @param class-string $handler
     */
    public function register(string $query, string $handler): void
    {
        $this->handlers[$query] = $handler;
    }

    public function dispatch(object $query): mixed
    {
        $queryClass = $query::class;

        throw_unless(isset($this->handlers[$queryClass]), RuntimeException::class, sprintf('No handler registered for query [%s].', $queryClass));

        $handler = $this->container->make($this->handlers[$queryClass]);

        return $handler->handle($query);
    }
}
