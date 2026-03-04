<?php

declare(strict_types=1);

namespace App\Presentation\Resources;

use App\Application\Features\Auth\DTOs\UserDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin UserDTO
 */
final class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'email_verified_at' => $this->emailVerifiedAt,
            'created_at'        => $this->createdAt,
            'updated_at'        => $this->updatedAt,
            'roles'             => array_map(fn ($r) => $r->value, $this->roles),
        ];
    }
}
