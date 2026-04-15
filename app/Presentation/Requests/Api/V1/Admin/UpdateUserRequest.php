<?php

declare(strict_types=1);

namespace App\Presentation\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // $this->route('user_id') gets the ID from the URL if named appropriately
        return [
            'name'     => ['sometimes', 'string', 'max:255'],
            'email'    => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $this->route('user_id')],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
        ];
    }
}
