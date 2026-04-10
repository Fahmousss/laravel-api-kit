<?php

namespace App\Domain\Authorization\Enums;

enum UserRole: string
{
    case ADMIN        = 'admin';
    case PROJECT_MANAGER = 'project_manager';
    case DEVELOPER    = 'developer';
    case REVIEWER     = 'reviewer';
    case REPORTER     = 'reporter';
    case VIEWER       = 'viewer';

    public function label(): string
    {
        return match($this) {
            self::ADMIN           => 'Administrator',
            self::PROJECT_MANAGER => 'Project Manager',
            self::DEVELOPER       => 'Developer',
            self::REVIEWER        => 'Reviewer',
            self::REPORTER        => 'Reporter',
            self::VIEWER          => 'Viewer',
        };
    }

    /** Roles that can manage project membership */
    public function canManageMembers(): bool
    {
        return in_array($this, [self::ADMIN, self::PROJECT_MANAGER]);
    }

    /** Roles that can create tickets */
    public function canCreateTicket(): bool
    {
        return in_array($this, [
            self::ADMIN, self::PROJECT_MANAGER,
            self::DEVELOPER, self::REPORTER,
        ]);
    }

    /** Roles that can transition ticket status */
    public function canTransitionStatus(): bool
    {
        return in_array($this, [
            self::ADMIN, self::PROJECT_MANAGER,
            self::DEVELOPER, self::REVIEWER,
        ]);
    }

    /** Roles that can approve/reject in review */
    public function canReview(): bool
    {
        return in_array($this, [self::ADMIN, self::PROJECT_MANAGER, self::REVIEWER]);
    }

    /** Roles that can close or reopen a ticket */
    public function canClose(): bool
    {
        return in_array($this, [self::ADMIN, self::PROJECT_MANAGER]);
    }

    /** Roles that can force-reopen a closed ticket */
    public function canForceReopen(): bool
    {
        return $this === self::ADMIN;
    }

    /** Roles that can delete a ticket */
    public function canDeleteTicket(): bool
    {
        return in_array($this, [self::ADMIN, self::PROJECT_MANAGER]);
    }
}
