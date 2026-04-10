<?php

declare(strict_types=1);

namespace App\Presentation\Requests\Api\V1\Ticket;

use App\Domain\Ticket\Enums\TicketPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority'    => ['sometimes', 'string', Rule::enum(TicketPriority::class)],
            'assignee_id' => ['nullable', 'uuid'],
            'due_date'    => ['nullable', 'date'],
        ];
    }
}
