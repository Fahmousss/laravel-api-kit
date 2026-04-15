<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Authentication;

use App\Application\Features\Authentication\DTOs\UserDTO;
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
        $dto = $this->resource;

        return [
            'id'                 => $dto->id,
            'name'               => $dto->name,
            'email'              => $dto->email,
            'email_verified_at'  => $dto->emailVerifiedAt,
            'created_at'         => $dto->createdAt,
            'updated_at'         => $dto->updatedAt,
            'system_role'        => $dto->systemRole->value,
            'system_role_label'  => $dto->systemRole->label(),
            'system_permissions' => $dto->systemRole->systemPermissions(),
        ];
    }
}
