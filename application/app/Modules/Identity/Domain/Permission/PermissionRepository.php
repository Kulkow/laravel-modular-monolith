<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Permission;

interface PermissionRepository
{
    public function findByCode(PermissionCode $code): ?Permission;

    /** @return Permission[] */
    public function findAll(): array;

    /** @return Permission[] */
    public function findByGroup(string $group): array;

    public function save(Permission $permission): int;
}
