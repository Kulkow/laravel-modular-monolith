<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Access;

use App\Modules\Identity\Domain\User\UserId;

interface AccessChecker
{
    public function hasPermission(UserId $userId, \BackedEnum $permission): bool;

    public function hasAnyPermission(UserId $userId, \BackedEnum ...$permissions): bool;

    public function hasAllPermissions(UserId $userId, \BackedEnum ...$permissions): bool;

    public function hasRole(UserId $userId, string $roleName): bool;

    /**
     * @throws AccessDeniedException
     */
    public function assertPermission(UserId $userId, \BackedEnum $permission): void;
}
