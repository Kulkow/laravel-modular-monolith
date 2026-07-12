<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Role\CreateRole;

use App\Modules\Identity\Domain\Permission\PermissionCode;
use App\Modules\Identity\Domain\Permission\PermissionRepository;
use App\Modules\Identity\Domain\Role\Role;
use App\Modules\Identity\Domain\Role\RoleName;
use App\Modules\Identity\Domain\Role\RoleRepository;

final readonly class CreateRoleUseCase
{
    public function __construct(
        private RoleRepository       $roleRepository,
        private PermissionRepository $permissionRepository,
    ) {}

    public function execute(CreateRoleDto $dto): int
    {
        $name = new RoleName($dto->name);

        if ($this->roleRepository->findByName($name) !== null) {
            throw new \DomainException("Роль '{$dto->name}' уже существует");
        }

        $role = Role::create($name, $dto->label);

        foreach ($dto->permissionCodes as $code) {
            $permission = $this->permissionRepository->findByCode(new PermissionCode($code));
            if ($permission !== null) {
                $role->givePermission($permission);
            }
        }

        return $this->roleRepository->save($role)->getValue();
    }
}
