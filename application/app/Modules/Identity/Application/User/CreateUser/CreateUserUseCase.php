<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\User\CreateUser;

use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Identity\Domain\Role\RoleRepository;
use App\Modules\Identity\Domain\User\User;
use App\Modules\Identity\Domain\User\UserEmail;
use App\Modules\Identity\Domain\User\UserPassword;
use App\Modules\Identity\Domain\User\UserRepository;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateUserResponse",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
    ],
    type: "object"
)]
final readonly class CreateUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
    ) {}

    public function execute(CreateUserDto $dto): int
    {
        $email = new UserEmail($dto->email);
        $password = new UserPassword($dto->password);

        if ($this->userRepository->findByEmail($email) !== null) {
            throw new \DomainException("Пользователь с email '{$dto->email}' уже существует");
        }

        $user = User::create($email, $dto->name,$password);

        if (!empty($dto->roleIds)) {
            foreach ($dto->roleIds as $roleId) {
                $role = $this->roleRepository->findById(new RoleId($roleId));
                if ($role !== null) {
                    $user->assignRole($role);
                }
            }
        }

        return $this->userRepository->save($user)->getValue();
    }
}
