<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Project;

use App\Application\Features\Project\DTOs\ProjectDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProjectDTO
 */
final class ProjectResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ProjectDTO $dto */
        $dto  = $this->resource;
        $role = $dto->myRole;

        return [
            'id'          => $dto->id,
            'owner_id'    => $dto->ownerId,
            'name'        => $dto->name,
            'slug'        => $dto->slug,
            'description' => $dto->description,
            'status'      => $dto->status->value,
            'created_at'  => $dto->createdAt,
            'updated_at'  => $dto->updatedAt,

            // RBAC — project-scoped role + all permission flags
            'my_role'       => $role?->value,
            'my_role_label' => $role?->label(),
            'permissions'   => $role?->permissions(),
        ];
    }
}
