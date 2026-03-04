<?php

declare(strict_types=1);

namespace App\Presentation\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class RegisterChildRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We use Spatie middleware / custom Role logic in the controller instead
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'posyandu_id'   => ['required', 'string', 'uuid'],
            'name'          => ['required', 'string', 'max:255'],
            'nik'           => ['nullable', 'string', 'size:16'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today'],
            'gender'        => ['required', 'string', 'in:male,female'],
        ];
    }
}
