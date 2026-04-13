<?php

declare(strict_types=1);

namespace App\Application\Features\Project\DTOs;

use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Project\Entities\Project;
use App\Domain\Project\Enums\ProjectStatus;

final readonly class ProjectDTO
{
    public function __construct(
        public string $id,
        public string $ownerId,
        public string $name,
        public string $slug,
        public ?string $description,
        public ProjectStatus $status,
        public ?UserRole $myRole,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromEntity(Project $project, ?UserRole $myRole = null): self
    {
        return new self(
            id: $project->id,
            ownerId: $project->ownerId,
            name: $project->name,
            slug: $project->slug,
            description: $project->description,
            status: $project->status,
            myRole: $myRole,
            createdAt: $project->createdAt ?? now()->toIso8601String(),
            updatedAt: $project->updatedAt ?? now()->toIso8601String(),
        );
    }
}
