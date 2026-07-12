<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Role\RevokeRole;

use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserRepository;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "RevokeRoleResponse",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Роль снята"),
    ],
    type: "object"
)]
final readonly class RevokeRoleUseCase
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function execute(RevokeRoleDto $dto): void
    {
        $user = $this->userRepository->findById(new UserId($dto->userId));
        if ($user === null) {
            throw new \DomainException("Пользователь #{$dto->userId} не найден");
        }

        $user->revokeRole(new RoleId($dto->roleId));

        $this->userRepository->save($user);
    }
}
