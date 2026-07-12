<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Access\Permissions;

enum IdentityPermission: string
{
    // Пользователи
    case ViewUsers   = 'identity.users.view';
    case ManageUsers = 'identity.users.manage';

    // Роли
    case ViewRoles   = 'identity.roles.view';
    case ManageRoles = 'identity.roles.manage';

    // Права доступа
    case ViewPermissions   = 'identity.permissions.view';
    case ManagePermissions = 'identity.permissions.manage';

    public function label(): string
    {
        return match ($this) {
            self::ViewUsers   => 'Просмотр пользователей',
            self::ManageUsers => 'Управление пользователями',
            self::ViewRoles   => 'Просмотр ролей',
            self::ManageRoles => 'Управление ролями',
            self::ViewPermissions   => 'Просмотр прав доступа',
            self::ManagePermissions => 'Управление правами доступа',
        };
    }

    public function group(): string
    {
        return 'Управление доступом';
    }
}
