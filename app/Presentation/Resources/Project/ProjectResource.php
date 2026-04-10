<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Project;

use App\Domain\Project\Entities\Project;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
final class ProjectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'owner_id'    => $this->ownerId,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'status'      => $this->status->value,
            'created_at'  => $this->createdAt,
            'updated_at'  => $this->updatedAt,
        ];
    }
}
