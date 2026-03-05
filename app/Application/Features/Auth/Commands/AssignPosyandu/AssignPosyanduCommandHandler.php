<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\AssignPosyandu;

use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Posyandus\Entities\PosyanduEntity;
use App\Domain\Posyandus\Repositories\PosyanduRepositoryInterface;
use DomainException;

final readonly class AssignPosyanduCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PosyanduRepositoryInterface $posyanduRepository,
    ) {}

    public function handle(AssignPosyanduCommand $command): bool
    {
        $user = $this->userRepository->findById($command->userId);
        throw_if(! $user instanceof UserEntity, UserNotFoundException::class, 'User not found');

        $posyandu = $this->posyanduRepository->findById($command->posyanduId);
        throw_if(! $posyandu instanceof PosyanduEntity, DomainException::class, 'Posyandu not found'); // Or a specific PosyanduNotFoundException

        $this->userRepository->assignPosyandu($command->userId, $command->posyanduId);

        return true;
    }
}
