<?php

namespace App\Domain\Project\Entities;

use App\Domain\Project\Enums\ProjectStatus;

class Project
{
    public function __construct(
        public readonly ?string $id,
        public readonly string  $ownerId,
        public string           $name,
        public string           $slug,
        public ?string          $description,
        public ProjectStatus    $status,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
    ) {}

    public function archive(): void
    {
        $this->status = ProjectStatus::ARCHIVED;
    }
}
