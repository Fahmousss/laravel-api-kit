<?php

declare(strict_types=1);

namespace App\Application\Features\Authorization\Common\Interfaces;

use App\Domain\Authorization\Enums\SystemRole;

/**
 * Resolves the SystemRole for a given user email.
 *
 * Lives in the Application layer so query/command handlers can depend on it
 * without coupling to infrastructure (config, DB, etc.).
 */
interface SystemRoleResolverInterface
{
    public function resolveForEmail(string $email): SystemRole;
}
