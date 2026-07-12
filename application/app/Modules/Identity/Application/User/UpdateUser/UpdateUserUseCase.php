<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\User\UpdateUser;

use App\Models\Teams\Team;
use App\Modules\Identity\Domain\User\UserEmail;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserRepository;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateUserResponse",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Пользователь обновлён"),
    ],
    type: "object"
)]
final readonly class UpdateUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function execute(UpdateUserDto $dto): void
    {
        $user = $this->userRepository->findById(new UserId($dto->userId));

        if ($user === null) {
            throw new \DomainException("Пользователь #{$dto->userId} не найден");
        }

        if ($dto->name !== null) {
            $user->changeName($dto->name);
        }

        if ($dto->email !== null) {
            $newEmail = new UserEmail($dto->email);
            $existing = $this->userRepository->findByEmail($newEmail);
            if ($existing !== null && !$existing->getId()->equals($user->getId())) {
                throw new \DomainException("Email '{$dto->email}' уже занят другим пользователем");
            }
            $user->changeEmail($newEmail);
        }

        $this->userRepository->save($user);
    }


}
