<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Role\DropRole;

use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Identity\Domain\Role\RoleRepository;
use App\Modules\Identity\Domain\User\User;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserRepository;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "DropRoleResponse",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Роль удалена"),
    ],
    type: "object"
)]
final readonly class DropRoleUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
    ) {}

    public function execute(RoleId $dto): void
    {
        $role = $this->roleRepository->findById($dto);
        if ($role === null) {
            throw new \DomainException("Роль #{$dto->getValue()} не найдена");
        }
        $users = $this->userRepository->findByRoleId($dto);
        if(! empty($users)) {
            $user = $users[0];
            /**
             * @var $user User
             */
            throw new \DomainException("Нельзя удалить роль есть пользователь #{$user->getEmail()->getValue()}");
        }
        $this->roleRepository->delete($dto);
    }
}
