<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\User\ListUsers;

use App\Modules\Identity\Application\DTO\EmployeeUserDto;
use App\Modules\Identity\Application\DTO\PaginatedUsersDto;
use App\Modules\Identity\Application\DTO\PermissionDto;
use App\Modules\Identity\Application\DTO\RoleDto;
use App\Modules\Identity\Application\DTO\UserDto;
use App\Modules\Identity\Domain\User\UserRepository;
use App\Modules\Personnel\Domain\Employee\Employee;
use App\Modules\Personnel\Domain\Employee\EmployeeRepository;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ListUsersResponse",
    properties: [
        new OA\Property(
            property: "data",
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/GetUserResponse")
        ),
    ],
    type: "object"
)]
final readonly class ListUsersUseCase
{
    public function __construct(
        private UserRepository     $userRepository
    )
    {
    }

    /** @return UserDto[] */
    public function execute(): array
    {
        $users = $this->userRepository->findAll();


        $items = array_map(function ($user) {
            $roleDtos = array_map(
                fn($role) => new RoleDto(
                    id: $role->getId()->getValue(),
                    name: $role->getName()->getValue(),
                    label: $role->getLabel(),
                    permissions: array_map(
                        fn($perm) => new PermissionDto(
                            id: $perm->getId() ?? 0,
                            code: $perm->getCode()->getValue(),
                            name: $perm->getName(),
                            group: $perm->getGroup(),
                        ),
                        $role->getPermissions()
                    ),
                ),
                $user->getRoles()
            );
            return new UserDto(
                id: $user->getId()->getValue(),
                email: $user->getEmail()->getValue(),
                name: $user->getName(),
                statusId: $user->getStatus()->getId(),
                statusName: $user->getStatus()->getName(),
                roles: $roleDtos,
                createdAt: $user->getCreatedAt()->format('Y-m-d H:i:s')
            );
        }, $users);

        return $items;
    }
}
