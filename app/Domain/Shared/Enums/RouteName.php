<?php

declare(strict_types=1);

namespace App\Domain\Shared\Enums;

enum RouteName: string
{
    // Auth
    case REGISTER           = 'api.v1.register';
    case LOGIN              = 'api.v1.login';
    case LOGOUT             = 'api.v1.logout';
    case ME                 = 'api.v1.me';

    // Email verification
    case EMAIL_VERIFY       = 'verification.verify';
    case EMAIL_RESEND       = 'verification.send';

    // Projects
    case PROJECTS_INDEX     = 'projects.index';
    case PROJECTS_STORE     = 'projects.store';
    case PROJECTS_ADD_MEMBER = 'projects.add-member';

    // Password reset
    case PASSWORD_EMAIL     = 'password.email';
    case PASSWORD_RESET     = 'password.reset';

    // Ticket
    case TICKETS_INDEX = 'tickets.index';
    case TICKETS_STORE = 'tickets.store';
    case TICKETS_SHOW = 'tickets.show';
    case TICKETS_UPDATE = 'tickets.update';
    case TICKETS_DESTROY = 'tickets.destroy';
    case TICKETS_TRANSITION = 'tickets.transition';
    case COMMENTS_INDEX = 'comments.index';
    case COMMENTS_STORE = 'comments.store';
    case COMMENTS_UPDATE = 'comments.update';
    case COMMENTS_DESTROY = 'comments.destroy';
    case ACTIVITY_LOG_INDEX = 'activity-log.index';
    case NOTIFICATIONS_INDEX = 'notifications.index';
    case NOTIFICATIONS_MARK_ALL_READ = 'notifications.mark-all-read';
}
