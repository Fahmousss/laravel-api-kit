<?php

namespace App\Infrastructure\Ticket\Persistence;

use App\Domain\Ticket\Entities\Ticket as TicketEntity;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;
use App\Domain\Shared\Enums\TicketStatus;
use App\Domain\Shared\Enums\TicketType;
use App\Domain\Shared\Enums\TicketPriority;
use App\Domain\Shared\Pagination\PaginatedResult;
use App\Infrastructure\Ticket\Models\Ticket as TicketModel;
use App\Infrastructure\Shared\Traits\EntityMapper;

class EloquentTicketRepository implements TicketRepositoryInterface
{
    use EntityMapper;

    public function findById(string $id): ?TicketEntity
    {
        $model = TicketModel::find($id);
        return $model ? $this->mapToEntity($model, TicketEntity::class) : null;
    }

    public function findByProjectAndNumber(string $projectId, int $number): ?TicketEntity
    {
        $model = TicketModel::where('project_id', $projectId)
                             ->where('ticket_number', $number)
                             ->first();
        return $model ? $this->mapToEntity($model, TicketEntity::class) : null;
    }

    public function save(TicketEntity $ticket): TicketEntity
    {
        $model = $ticket->id
            ? TicketModel::findOrFail($ticket->id)
            : new TicketModel();

        $model->fill([
            'project_id'    => $ticket->projectId,
            'reporter_id'   => $ticket->reporterId,
            'assignee_id'   => $ticket->assigneeId,
            'ticket_number' => $ticket->ticketNumber,
            'title'         => $ticket->title,
            'description'   => $ticket->description,
            'type'          => $ticket->type->value,
            'status'        => $ticket->status->value,
            'priority'      => $ticket->priority->value,
            'due_date'      => $ticket->dueDate,
            'resolved_at'   => $ticket->resolvedAt,
        ])->save();

        return $this->mapToEntity($model->fresh(), TicketEntity::class);
    }

    public function delete(string $id): void
    {
        TicketModel::destroy($id);
    }

    public function nextTicketNumber(string $projectId): int
    {
        return (TicketModel::where('project_id', $projectId)->max('ticket_number') ?? 0) + 1;
    }

    public function paginate(string $projectId, array $filters, int $perPage, int $page): PaginatedResult
    {
        $query = TicketModel::where('project_id', $projectId);

        if (isset($filters['status']))   $query->where('status', $filters['status']);
        if (isset($filters['type']))     $query->where('type', $filters['type']);
        if (isset($filters['priority'])) $query->where('priority', $filters['priority']);
        if (isset($filters['assignee'])) $query->where('assignee_id', $filters['assignee']);

        $paginator = $query->orderByDesc('created_at')->paginate($perPage, ['*'], 'page', $page);

        return new PaginatedResult(
            items:       array_map(fn(TicketModel $m) => $this->mapToEntity($m, TicketEntity::class), $paginator->items()),
            total:       $paginator->total(),
            perPage:     $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage:    $paginator->lastPage(),
        );
    }
}
