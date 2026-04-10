<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Common\Interfaces;

interface UserVerifiedEventDispatcherInterface
{
    public function dispatch(int $userId): void;
}
