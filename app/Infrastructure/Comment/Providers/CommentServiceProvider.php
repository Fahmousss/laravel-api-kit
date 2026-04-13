<?php

declare(strict_types=1);

namespace App\Infrastructure\Comment\Providers;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Comment\Commands\CreateComment\CreateCommentCommand;
use App\Application\Features\Comment\Commands\CreateComment\CreateCommentCommandHandler;
use App\Application\Features\Comment\Commands\DeleteComment\DeleteCommentCommand;
use App\Application\Features\Comment\Commands\DeleteComment\DeleteCommentCommandHandler;
use App\Application\Features\Comment\Commands\EditComment\EditCommentCommand;
use App\Application\Features\Comment\Commands\EditComment\EditCommentCommandHandler;
use App\Application\Features\Comment\Queries\ListComments\ListCommentsQuery;
use App\Application\Features\Comment\Queries\ListComments\ListCommentsQueryHandler;
use App\Domain\Comment\Repositories\CommentRepositoryInterface;
use App\Infrastructure\Comment\Persistence\EloquentCommentRepository;
use Illuminate\Support\ServiceProvider;

final class CommentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CommentRepositoryInterface::class, EloquentCommentRepository::class);
    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(CreateCommentCommand::class, CreateCommentCommandHandler::class);
        $commandBus->register(EditCommentCommand::class, EditCommentCommandHandler::class);
        $commandBus->register(DeleteCommentCommand::class, DeleteCommentCommandHandler::class);

        $queryBus->register(ListCommentsQuery::class, ListCommentsQueryHandler::class);
    }
}
