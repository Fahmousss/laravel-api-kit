<?php

declare(strict_types=1);

namespace App\Infrastructure\Children\Providers;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Children\Commands\CreateChild\CreateChildCommand;
use App\Application\Features\Children\Commands\CreateChild\CreateChildCommandHandler;
use App\Application\Features\Children\Queries\GetChildrenByPosyandu\GetChildrenByPosyanduQuery;
use App\Application\Features\Children\Queries\GetChildrenByPosyandu\GetChildrenByPosyanduQueryHandler;
use App\Domain\Children\Repositories\ChildRepositoryInterface;
use App\Infrastructure\Children\Persistence\EloquentChildRepository;
use Illuminate\Support\ServiceProvider;

final class ChildrenServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ChildRepositoryInterface::class, EloquentChildRepository::class);
    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(CreateChildCommand::class, CreateChildCommandHandler::class);

        $queryBus->register(GetChildrenByPosyanduQuery::class, GetChildrenByPosyanduQueryHandler::class);
    }
}
