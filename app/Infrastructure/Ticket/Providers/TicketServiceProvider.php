<?php

namespace App\Infrastructure\Ticket\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;
use App\Infrastructure\Ticket\Persistence\EloquentTicketRepository;
use App\Application\Features\Ticket\Common\Interfaces\TicketActivityServiceInterface;
use App\Infrastructure\Ticket\Services\TicketActivityService;

// Commands
use App\Application\Features\Ticket\Commands\CreateTicket\CreateTicketCommandHandler;
use App\Application\Features\Ticket\Commands\UpdateTicket\UpdateTicketCommandHandler;
use App\Application\Features\Ticket\Commands\DeleteTicket\DeleteTicketCommandHandler;
use App\Application\Features\Ticket\Commands\TransitionStatus\TransitionStatusCommandHandler;
// Queries
use App\Application\Features\Ticket\Queries\GetTicket\GetTicketQueryHandler;
use App\Application\Features\Ticket\Queries\ListTickets\ListTicketsQueryHandler;
// Command/Query classes (for bus registration)
use App\Application\Features\Ticket\Commands\CreateTicket\CreateTicketCommand;
use App\Application\Features\Ticket\Commands\UpdateTicket\UpdateTicketCommand;
use App\Application\Features\Ticket\Commands\DeleteTicket\DeleteTicketCommand;
use App\Application\Features\Ticket\Commands\TransitionStatus\TransitionStatusCommand;
use App\Application\Features\Ticket\Queries\GetTicket\GetTicketQuery;
use App\Application\Features\Ticket\Queries\ListTickets\ListTicketsQuery;
use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;

class TicketServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TicketRepositoryInterface::class, EloquentTicketRepository::class);
        $this->app->bind(TicketActivityServiceInterface::class, TicketActivityService::class);
    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(CreateTicketCommand::class,    CreateTicketCommandHandler::class);
        $commandBus->register(UpdateTicketCommand::class,    UpdateTicketCommandHandler::class);
        $commandBus->register(DeleteTicketCommand::class,    DeleteTicketCommandHandler::class);
        $commandBus->register(TransitionStatusCommand::class, TransitionStatusCommandHandler::class);

        $queryBus->register(GetTicketQuery::class,    GetTicketQueryHandler::class);
        $queryBus->register(ListTicketsQuery::class,  ListTicketsQueryHandler::class);
    }
}
