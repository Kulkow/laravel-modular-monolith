<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Role\AssignRole;

use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Identity\Domain\Role\RoleRepository;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserRepository;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AssignRoleResponse",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Роль назначена"),
    ],
    type: "object"
)]
final readonly class AssignRoleUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
    ) {}

    public function execute(AssignRoleDto $dto): void
    {
        $user = $this->userRepository->findById(new UserId($dto->userId));
        if ($user === null) {
            throw new \DomainException("Пользователь #{$dto->userId} не найден");
        }

        $role = $this->roleRepository->findById(new RoleId($dto->roleId));
        if ($role === null) {
            throw new \DomainException("Роль #{$dto->roleId} не найдена");
        }

        $user->assignRole($role);

        $this->userRepository->save($user);
    }
}
