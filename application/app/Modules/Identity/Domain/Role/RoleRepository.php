<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Role;

interface RoleRepository
{
    public function findById(RoleId $id): ?Role;

    public function findByName(RoleName $name): ?Role;

    /** @return Role[] */
    public function findAll(): array;

    public function save(Role $role): RoleId;

    public function delete(RoleId $id): void;
}
