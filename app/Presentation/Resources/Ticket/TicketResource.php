<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Ticket;

use App\Application\Features\Ticket\DTOs\TicketDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TicketDTO
 */
final class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var TicketDTO $dto */
        $dto = $this->resource;

        return [
            'id'            => $dto->id,
            'ticket_number' => $dto->ticketNumber,
            'project_id'    => $dto->projectId,
            'reporter_id'   => $dto->reporterId,
            'assignee_id'   => $dto->assigneeId,
            'title'         => $dto->title,
            'description'   => $dto->description,
            'type'          => [
                'value' => $dto->type->value,
                'label' => $dto->type->label(),
            ],
            'status' => [
                'value'               => $dto->status->value,
                'label'               => $dto->status->label(),
                'allowed_transitions' => array_map(
                    fn ($s) => $s->value,
                    $dto->status->allowedTransitions()
                ),
            ],
            'priority'    => $dto->priority->value,
            'due_date'    => $dto->dueDate,
            'resolved_at' => $dto->resolvedAt,
            'created_at'  => $dto->createdAt,
            'updated_at'  => $dto->updatedAt,
        ];
    }
}
