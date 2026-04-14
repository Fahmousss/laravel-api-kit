<?php

declare(strict_types=1);

namespace App\Infrastructure\Authorization\Services;

use App\Application\Features\Authorization\Common\Interfaces\SystemRoleResolverInterface;
use App\Domain\Authorization\Enums\SystemRole;

/**
 * Resolves SystemRole by checking the user's email against the
 * admin_emails list defined in config/authorization.php.
 *
 * No database column required — role is derived purely from config.
 */
final class ConfigSystemRoleResolver implements SystemRoleResolverInterface
{
    /** @var string */
    private string $adminEmail;

    public function __construct()
    {
        $this->adminEmail = config('auth.admin_email', 'admin@example.com');
    }

    public function resolveForEmail(string $email): SystemRole
    {
        return $email === $this->adminEmail
            ? SystemRole::SYSTEM_ADMIN
            : SystemRole::MEMBER;
    }
}
