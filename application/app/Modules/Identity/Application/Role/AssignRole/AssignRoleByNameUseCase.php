<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Role\AssignRole;

use App\Modules\Identity\Domain\Role\RoleName;
use App\Modules\Identity\Domain\Role\RoleRepository;
use App\Modules\Identity\Domain\User\UserEmail;
use App\Modules\Identity\Domain\User\UserRepository;



final readonly class AssignRoleByNameUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
    ) {}

    public function execute(AssignRoleByNameDto $dto): void
    {
        $user = $this->userRepository->findByEmail(new UserEmail($dto->userEmail));
        if ($user === null) {
            throw new \DomainException("Пользователь #{$dto->userEmail} не найден");
        }

        $role = $this->roleRepository->findByName(new RoleName($dto->roleName));
        if ($role === null) {
            throw new \DomainException("Роль #{$dto->roleName} не найдена");
        }

        $user->assignRole($role);

        $this->userRepository->save($user);
    }
}
