<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Providers;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Project\Commands\AddMember\AddMemberCommand;
use App\Application\Features\Project\Commands\AddMember\AddMemberCommandHandler;
use App\Application\Features\Project\Commands\CreateProject\CreateProjectCommand;
use App\Application\Features\Project\Commands\CreateProject\CreateProjectCommandHandler;
use App\Application\Features\Project\Queries\GetProject\GetProjectQuery;
use App\Application\Features\Project\Queries\GetProject\GetProjectQueryHandler;
use App\Application\Features\Project\Queries\GetProjectMembers\GetProjectMemberRoleQuery;
use App\Application\Features\Project\Queries\GetProjectMembers\GetProjectMemberRoleQueryHandler;
use App\Application\Features\Project\Queries\GetRolesByUser\GetRolesByUserQuery;
use App\Application\Features\Project\Queries\GetRolesByUser\GetRolesByUserQueryHandler;
use App\Application\Features\Project\Queries\ListProjects\ListProjectsQuery;
use App\Application\Features\Project\Queries\ListProjects\ListProjectsQueryHandler;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Infrastructure\Project\Persistence\EloquentProjectMemberRepository;
use App\Infrastructure\Project\Persistence\EloquentProjectRepository;
use Illuminate\Support\ServiceProvider;

final class ProjectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
        $this->app->bind(ProjectMemberRepositoryInterface::class, EloquentProjectMemberRepository::class);
    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(CreateProjectCommand::class, CreateProjectCommandHandler::class);
        $commandBus->register(AddMemberCommand::class, AddMemberCommandHandler::class);

        $queryBus->register(ListProjectsQuery::class, ListProjectsQueryHandler::class);
        $queryBus->register(GetProjectQuery::class, GetProjectQueryHandler::class);
        $queryBus->register(GetProjectMemberRoleQuery::class, GetProjectMemberRoleQueryHandler::class);
        $queryBus->register(GetRolesByUserQuery::class, GetRolesByUserQueryHandler::class);
    }
}
