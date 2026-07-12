<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Role\UpdateRole;

use App\Modules\Identity\Domain\Permission\PermissionCode;
use App\Modules\Identity\Domain\Permission\PermissionRepository;
use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Identity\Domain\Role\RoleName;
use App\Modules\Identity\Domain\Role\RoleRepository;

final readonly class UpdateRoleUseCase
{
    public function __construct(
        private RoleRepository       $roleRepository,
        private PermissionRepository $permissionRepository,
    ) {}

    public function execute(UpdateRoleDto $dto): void
    {
        $role = $this->roleRepository->findById(new RoleId($dto->roleId));

        if ($role === null) {
            throw new \DomainException("Роль #{$dto->roleId} не найдена");
        }

        if ($dto->name !== null) {
            $newName = new RoleName($dto->name);
            $existing = $this->roleRepository->findByName($newName);
            if ($existing !== null && $existing->getId()->getValue() !== $dto->roleId) {
                throw new \DomainException("Роль '{$dto->name}' уже существует");
            }
            $role->rename($newName);
        }

        if ($dto->label !== null) {
            $role->relabel($dto->label);
        }

        if ($dto->permissionCodes !== null) {
            $permissions = [];
            foreach ($dto->permissionCodes as $code) {
                $permission = $this->permissionRepository->findByCode(new PermissionCode($code));
                if ($permission !== null) {
                    $permissions[] = $permission;
                }
            }
            $role->syncPermissions($permissions);
        }

        $this->roleRepository->save($role);
    }
}
