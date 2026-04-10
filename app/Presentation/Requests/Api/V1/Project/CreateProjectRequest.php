<?php

declare(strict_types=1);

namespace App\Presentation\Requests\Api\V1\Project;

use Illuminate\Foundation\Http\FormRequest;

final class CreateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:100', 'alpha_dash', 'unique:App\Infrastructure\Project\Models\Project,slug'],
            'description' => ['nullable', 'string'],
        ];
    }
}
