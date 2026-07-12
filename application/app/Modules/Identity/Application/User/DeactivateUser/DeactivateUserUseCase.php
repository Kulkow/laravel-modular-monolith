<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\User\DeactivateUser;

use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserRepository;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "DeactivateUserResponse",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Пользователь деактивирован"),
    ],
    type: "object"
)]
final readonly class DeactivateUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function execute(int $userId): void
    {
        $user = $this->userRepository->findById(new UserId($userId));

        if ($user === null) {
            throw new \DomainException("Пользователь #{$userId} не найден");
        }

        $user->deactivate();

        $this->userRepository->save($user);
    }
}
