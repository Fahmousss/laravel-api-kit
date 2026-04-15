<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Commands\DeleteProject;

use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class DeleteProjectCommand
{
    public function __construct(
        public ActorContext $actor,
        public string $id,
    ) {}
}
