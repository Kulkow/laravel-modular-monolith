<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Access;


use App\Modules\Identity\Domain\Access\AccessChecker;
use App\Modules\Identity\Domain\Access\AccessDeniedException;
use App\Modules\Identity\Domain\Role\PredefinedRole;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Infrastructure\Persistence\User\UserModel;


final class SpatieAccessChecker implements AccessChecker
{
    public function hasPermission(UserId $userId, \BackedEnum $permission): bool
    {
        [$superAdmin, $granted] = $this->resolveAccess($userId);
        return $superAdmin || ($granted[$permission->value] ?? false);
    }

    public function hasAnyPermission(UserId $userId, \BackedEnum ...$permissions): bool
    {
        [$superAdmin, $granted] = $this->resolveAccess($userId);

        if ($superAdmin) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($granted[$permission->value] ?? false) {
                return true;
            }
        }

        return false;
    }

    public function hasAllPermissions(UserId $userId, \BackedEnum ...$permissions): bool
    {
        [$superAdmin, $granted] = $this->resolveAccess($userId);

        if ($superAdmin) {
            return true;
        }

        foreach ($permissions as $permission) {
            if (!($granted[$permission->value] ?? false)) {
                return false;
            }
        }

        return true;
    }

    public function hasRole(UserId $userId, string $roleName): bool
    {
        $user = UserModel::query()->with('roles')->find($userId->getValue());
        if ($user === null) {
            return false;
        }

        foreach ($user->roles as $role) {
            if ($role->name === $roleName) {
                return true;
            }
        }

        return false;
    }

    public function assertPermission(UserId $userId, \BackedEnum $permission): void
    {
        if (!$this->hasPermission($userId, $permission)) {
            throw new AccessDeniedException(
                permissionCode: (string) $permission->label(),
                userId:         $userId->getValue(),
            );
        }
    }

    /**
     * Single DB query: loads user with roles, checks fullAllow() flag first,
     * then builds the granted-permissions map.
     *
     * @return array{0: bool, 1: array<string, true>}  [$isSuperAdmin, $granted]
     */
    private function resolveAccess(UserId $userId): array
    {
        $user = UserModel::query()->with('roles')->find($userId->getValue());
        if ($user === null) {
            return [false, []];
        }

        $granted = [];

        foreach ($user->roles as $role) {
            $predefined = PredefinedRole::tryFrom($role->name);
            if ($predefined === null) {
                continue;
            }

            if ($predefined->fullAllow()) {
                return [true, []];
            }

            foreach ($predefined->permissions() as $permission) {
                $granted[$permission->value] = true;
            }
        }

        return [false, $granted];
    }
}
