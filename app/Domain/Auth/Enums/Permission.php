<?php

declare(strict_types=1);

namespace App\Domain\Auth\Enums;

enum Permission: string
{
    case MANAGE_USERS             = 'manage-users';
    case MANAGE_POSYANDUS         = 'manage-posyandus';
    case VIEW_ALL_CHILDREN        = 'view-all-children';
    case MANAGE_ALL_CHILDREN      = 'manage-all-children';
    case VIEW_POSYANDU_CHILDREN   = 'view-posyandu-children';
    case MANAGE_POSYANDU_CHILDREN = 'manage-posyandu-children';
    case VIEW_CLUSTERS            = 'view-clusters';
    case VIEW_DASHBOARD_STATS     = 'view-dashboard-stats';
}
