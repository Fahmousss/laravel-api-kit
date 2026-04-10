<?php

declare(strict_types=1);

namespace App\Presentation\Requests\Api\V1\Project;

use App\Domain\Authorization\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class AddMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'role'    => ['required', 'string', Rule::enum(UserRole::class)],
        ];
    }
}
