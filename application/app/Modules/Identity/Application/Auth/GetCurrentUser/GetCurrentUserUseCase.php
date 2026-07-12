<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Auth\GetCurrentUser;

use App\Modules\Identity\Application\DTO\RoleDto;
use App\Modules\Identity\Domain\Role\PredefinedRole;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserRepository;

final readonly class GetCurrentUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function execute(int $userId): CurrentUserDto
    {
        $user = $this->userRepository->findById(new UserId($userId));

        if ($user === null) {
            throw new \DomainException("Пользователь #{$userId} не найден");
        }

        $isSuperAdmin   = false;
        $permissionsMap = [];

        foreach ($user->getRoles() as $role) {
            $predefined = PredefinedRole::tryFrom($role->getName()->getValue());
            if ($predefined === null) {
                continue;
            }

            if ($predefined->fullAllow()) {
                $isSuperAdmin = true;
                break;
            }

            foreach ($predefined->permissions() as $permission) {
                $permissionsMap[$permission->value] = $permission->value;
            }
        }

        if ($isSuperAdmin) {
            $permissionsMap = $this->collectAllPermissions();
        }

        $roleDtos = array_map(
            fn($role) => new RoleDto(
                id:          $role->getId()->getValue(),
                name:        $role->getName()->getValue(),
                label:       $role->getLabel(),
                permissions: [],
            ),
            $user->getRoles()
        );

        return new CurrentUserDto(
            id:          $user->getId()->getValue(),
            email:       $user->getEmail()->getValue(),
            name:        $user->getName(),
            statusId:    $user->getStatus()->getId(),
            statusName:  $user->getStatus()->getName(),
            roles:       $roleDtos,
            createdAt:   $user->getCreatedAt()->format('Y-m-d H:i:s'),
            fullAccess:  $isSuperAdmin,
            permissions: array_values($permissionsMap),
        );
    }

    /** @return array<string, string> */
    private function collectAllPermissions(): array
    {
        $all = [];
        foreach (PredefinedRole::cases() as $role) {
            if ($role->fullAllow()) {
                continue;
            }
            foreach ($role->permissions() as $permission) {
                $all[$permission->value] = $permission->value;
            }
        }
        return $all;
    }
}
