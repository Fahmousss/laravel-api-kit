<?php

declare(strict_types=1);

namespace App\Presentation\Requests\Api\V1\Ticket;

use App\Domain\Ticket\Enums\TicketPriority;
use App\Domain\Ticket\Enums\TicketType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type'        => ['required', 'string', Rule::enum(TicketType::class)],
            'priority'    => ['required', 'string', Rule::enum(TicketPriority::class)],
            'assignee_id' => [
                'nullable', 
                'uuid', 
                Rule::exists('project_members', 'user_id')->where(function ($query) {
                    $query->where('project_id', $this->route('project_id'));
                }),
            ],
            'due_date'    => ['nullable', 'date'],
            'label_ids'   => ['nullable', 'array'],
            'label_ids.*' => ['uuid'],
        ];
    }
}
