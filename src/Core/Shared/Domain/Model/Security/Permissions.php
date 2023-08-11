<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Model\Security;

interface Permissions
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    final public const ALL_ROLES = [
        self::ROLE_USER,
        self::ROLE_EMPLOYEE,
        self::ROLE_ADMIN,
    ];

    public const EDIT_ACTION = 'edit';
    public const VIEW_ACTION = 'view';
    public const ADD_ACTION = 'add';
    public const DELETE_ACTION = 'delete';
    public const ADMIN_ACTION = 'admin';

    public const ALL_ACTIONS = [
        self::EDIT_ACTION,
        self::VIEW_ACTION,
        self::ADD_ACTION,
        self::DELETE_ACTION,
        self::ADMIN_ACTION,
    ];
}
