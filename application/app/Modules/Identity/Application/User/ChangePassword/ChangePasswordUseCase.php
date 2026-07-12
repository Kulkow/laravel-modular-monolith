<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\User\ChangePassword;

use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserPassword;
use App\Modules\Identity\Domain\User\UserRepository;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ChangePasswordUserResponse",
    properties: [
        new OA\Property(property: "message", type: "string"),
    ],
    type: "object"
)]
final readonly class ChangePasswordUseCase
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function execute(ChangePasswordDto $dto): string
    {
        $this->userRepository->changePassword(new UserId($dto->id), new UserPassword($dto->password));
        return 'Пароль изменен';
    }
}
