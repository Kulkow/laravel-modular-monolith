<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\User\GetUser;

use App\Modules\Identity\Application\DTO\EmployeeUserDto;
use App\Modules\Identity\Application\DTO\PermissionDto;
use App\Modules\Identity\Application\DTO\RoleDto;
use App\Modules\Identity\Application\DTO\UserDto;
use App\Modules\Identity\Domain\Permission\Permission;
use App\Modules\Identity\Domain\Role\Role;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserRepository;
use App\Modules\Personnel\Application\DTO\EmployeeDto;

use App\Modules\Personnel\Domain\Employee\EmployeeRepository;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "GetUserResponse",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "email", type: "string", example: "user@example.com"),
        new OA\Property(property: "name", type: "string", example: "Иван Иванов"),
        new OA\Property(
            property: "status",
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "name", type: "string", example: "Активный"),
            ]
        ),
        new OA\Property(
            property: "roles",
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "admin"),
                    new OA\Property(property: "label", type: "string", example: "Администратор"),
                ],
                type: "object"
            )
        ),
        new OA\Property(property: "created_at", type: "string", example: "2025-01-01 00:00:00"),
    ],
    type: "object"
)]
final readonly class GetUserUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function execute(int $userId): UserDto
    {
        $user = $this->userRepository->findById(new UserId($userId));

        if ($user === null) {
            throw new \DomainException("Пользователь #{$userId} не найден");
        }

        $roleDtos = array_map(
            fn(Role $role) => new RoleDto(
                id:          $role->getId()->getValue(),
                name:        $role->getName()->getValue(),
                label:       $role->getLabel(),
                permissions: array_map(
                    fn(Permission $perm) => new PermissionDto(
                        id:    $perm->getId() ?? 0,
                        code:  $perm->getCode()->getValue(),
                        name:  $perm->getName(),
                        group: $perm->getGroup(),
                    ),
                    $role->getPermissions()
                ),
            ),
            $user->getRoles()
        );

        return new UserDto(
            id:         $user->getId()->getValue(),
            email:      $user->getEmail()->getValue(),
            name:       $user->getName(),
            statusId:   $user->getStatus()->getId(),
            statusName: $user->getStatus()->getName(),
            roles:      $roleDtos,
            createdAt:  $user->getCreatedAt()->format('Y-m-d H:i:s')
        );
    }
}
