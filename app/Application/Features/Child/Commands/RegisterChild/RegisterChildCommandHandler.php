<?php

declare(strict_types=1);

namespace App\Application\Features\Child\Commands\RegisterChild;

use App\Domain\Child\Entities\ChildEntity;
use App\Domain\Child\Repositories\ChildRepositoryInterface;
use Illuminate\Support\Str;

final class RegisterChildCommandHandler
{
    public function __construct(
        private readonly ChildRepositoryInterface $repository
    ) {}

    public function handle(RegisterChildCommand $command): ChildEntity
    {
        $child = new ChildEntity(
            id: (string) Str::uuid(),
            posyanduId: $command->posyanduId,
            name: $command->name,
            nik: $command->nik,
            dateOfBirth: $command->dateOfBirth,
            gender: $command->gender,
        );

        return $this->repository->save($child);
    }
}
