<?php

namespace App\Application\Features\Ticket\DTOs;

use App\Domain\Ticket\Enums\TicketType;
use App\Domain\Ticket\Enums\TicketPriority;

readonly class CreateTicketDTO
{
    public function __construct(
        public string         $projectId,
        public string         $reporterId,
        public string         $title,
        public ?string        $description,
        public TicketType     $type,
        public TicketPriority $priority,
        public ?string        $assigneeId,
        public ?string        $dueDate,
        public array          $labelIds = [],
    ) {}
}
