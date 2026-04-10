<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Ticket;

use App\Domain\Ticket\Entities\Ticket;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Ticket */
final class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'ticket_number' => $this->ticketNumber,
            'project_id'    => $this->projectId,
            'reporter_id'   => $this->reporterId,
            'assignee_id'   => $this->assigneeId,
            'title'         => $this->title,
            'description'   => $this->description,
            'type'          => [
                'value' => $this->type->value,
                'label' => $this->type->label(),
            ],
            'status' => [
                'value'               => $this->status->value,
                'label'               => $this->status->label(),
                'allowed_transitions' => array_map(
                    fn ($s) => $s->value,
                    $this->status->allowedTransitions()
                ),
            ],
            'priority'    => $this->priority->value,
            'due_date'    => $this->dueDate,
            'resolved_at' => $this->resolvedAt,
            'created_at'  => $this->createdAt,
            'updated_at'  => $this->updatedAt,
        ];
    }
}
