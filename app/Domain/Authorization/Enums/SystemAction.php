<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Enums;

enum SystemAction: string
{
    case MANAGE_MEMBERS        = 'manageMembers';
    case CREATE_PROJECT        = 'createProject';
    case CREATE_TICKET         = 'createTicket';
    case UPDATE_TICKET         = 'updateTicket';
    case DELETE_TICKET         = 'deleteTicket';
    case TRANSITION_STATUS     = 'transitionStatus';
    case REVIEW                = 'review';
    case CLOSE                 = 'close';
    case FORCE_REOPEN          = 'forceReopen';
    case POST_INTERNAL_COMMENT = 'postInternalComment';
    case ACCESS_ADMIN_PANEL    = 'accessAdminPanel';
}

