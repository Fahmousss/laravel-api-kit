<?php

namespace App\Infrastructure\Project\Providers;

use App\Application\Features\Project\Queries\GetProjectMembers\GetProjectMemberRoleQuery;
use App\Application\Features\Project\Queries\GetProjectMembers\GetProjectMemberRoleQueryHandler;
use Illuminate\Support\ServiceProvider;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;
use App\Infrastructure\Project\Persistence\EloquentProjectRepository;
use App\Infrastructure\Project\Persistence\EloquentProjectMemberRepository;

use App\Application\Features\Project\Commands\CreateProject\CreateProjectCommand;
use App\Application\Features\Project\Commands\CreateProject\CreateProjectCommandHandler;
use App\Application\Features\Project\Commands\AddMember\AddMemberCommand;
use App\Application\Features\Project\Commands\AddMember\AddMemberCommandHandler;
use App\Application\Features\Project\Queries\ListProjects\ListProjectsQuery;
use App\Application\Features\Project\Queries\ListProjects\ListProjectsQueryHandler;
use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;

class ProjectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
        $this->app->bind(ProjectMemberRepositoryInterface::class, EloquentProjectMemberRepository::class);
    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(CreateProjectCommand::class, CreateProjectCommandHandler::class);
        $commandBus->register(AddMemberCommand::class,     AddMemberCommandHandler::class);

        $queryBus->register(ListProjectsQuery::class, ListProjectsQueryHandler::class);
        $queryBus->register(GetProjectMemberRoleQuery::class, GetProjectMemberRoleQueryHandler::class);
    }
}
