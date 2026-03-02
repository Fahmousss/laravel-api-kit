<?php

declare(strict_types=1);

namespace App\Application\Contracts;

interface QueryBusInterface
{
    public function dispatch(object $query): mixed;
}
