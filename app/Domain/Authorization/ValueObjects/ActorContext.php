<?php

declare(strict_types=1);

namespace App\Domain\Authorization\ValueObjects;

use App\Domain\Authorization\Enums\SystemAction;
use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Authorization\Enums\SystemRole;
use App\Domain\Authorization\Exceptions\UnauthorizedActionException;

/**
 * Immutable value object representing the authenticated actor
 * within a specific request context.
 *
 * Carries the actor's identity and their resolved roles.
 * Travels typed through Presentation → Application.
 * Never constructed outside Infrastructure/Presentation layers.
 */
final readonly class ActorContext
{
    public function __construct(
        public string      $userId,
        public SystemRole  $systemRole,
        public ?UserRole   $projectRole = null,
    ) {}

    // ── System-level checks ────────────────────────────────────────────

    public function isSystemAdmin(): bool
    {
        return $this->systemRole->isSystemAdmin();
    }

    // ── Project-level checks ───────────────────────────────────────────

    public function hasProjectRole(): bool
    {
        return $this->projectRole !== null;
    }

    public function canManageMembers(): bool
    {
        return $this->projectRole?->canManageMembers() ?? false;
    }

    public function canCreateTicket(): bool
    {
        return $this->projectRole?->canCreateTicket() ?? false;
    }

    public function canUpdateTicket(): bool
    {
        return $this->projectRole?->canUpdateTicket() ?? false;
    }

    public function canDeleteTicket(): bool
    {
        return $this->projectRole?->canDeleteTicket() ?? false;
    }

    public function canTransitionStatus(): bool
    {
        return $this->projectRole?->canTransitionStatus() ?? false;
    }

    public function canReview(): bool
    {
        return $this->projectRole?->canReview() ?? false;
    }

    public function canClose(): bool
    {
        return $this->projectRole?->canClose() ?? false;
    }

    public function canCreateProject(): bool
    {
        return true; // Any authenticated member may create a project
    }

    public function canForceReopen(): bool
    {
        return $this->projectRole?->canForceReopen() ?? false;
    }

    public function canPostInternalComment(): bool
    {
        return $this->projectRole?->canPostInternalComment() ?? false;
    }

    // ── Assertion helpers ──────────────────────────────────────────────

    /**
     * Throws UnauthorizedActionException if the actor cannot perform the action.
     * Use in command handlers to enforce authorization in one line.
     *
     * @example $actor->assertCan('createTicket')
     */
    public function assertCan(SystemAction $action): void
    {
        $allowed = match($action) {
            SystemAction::MANAGE_MEMBERS        => $this->canManageMembers(),
            SystemAction::CREATE_PROJECT        => $this->canCreateProject(),
            SystemAction::CREATE_TICKET         => $this->canCreateTicket(),
            SystemAction::UPDATE_TICKET         => $this->canUpdateTicket(),
            SystemAction::DELETE_TICKET         => $this->canDeleteTicket(),
            SystemAction::TRANSITION_STATUS     => $this->canTransitionStatus(),
            SystemAction::REVIEW                => $this->canReview(),
            SystemAction::CLOSE                 => $this->canClose(),
            SystemAction::FORCE_REOPEN          => $this->canForceReopen(),
            SystemAction::POST_INTERNAL_COMMENT => $this->canPostInternalComment(),
            SystemAction::ACCESS_ADMIN_PANEL    => $this->isSystemAdmin(),
        };

        if (! $allowed) {
            throw UnauthorizedActionException::forAction($action);
        }
    }
}
