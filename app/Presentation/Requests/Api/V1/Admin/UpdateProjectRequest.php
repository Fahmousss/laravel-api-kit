<?php

declare(strict_types=1);

namespace App\Presentation\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Domain\Project\Enums\ProjectStatus;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status'      => ['sometimes', Rule::enum(ProjectStatus::class)],
        ];
    }
}
