<?php

declare(strict_types=1);

namespace App\Presentation\Requests\Api\V1\Ticket;

use App\Domain\Ticket\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class TransitionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'  => ['required', 'string', Rule::enum(TicketStatus::class)],
            'comment' => ['nullable', 'string'],
        ];
    }
}
