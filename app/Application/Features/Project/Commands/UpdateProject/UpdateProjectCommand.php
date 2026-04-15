<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Commands\UpdateProject;

use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class UpdateProjectCommand
{
    public function __construct(
        public ActorContext $actor,
        public string  $id,
        public ?string $name = null,
        public ?string $description = null,
        public ?string $status = null,
    ) {}
}
