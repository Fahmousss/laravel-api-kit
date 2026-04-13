<?php

declare(strict_types=1);

namespace App\Presentation\Requests\Api\V1\Comment;

use Illuminate\Foundation\Http\FormRequest;

final class EditCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:1'],
        ];
    }
}
