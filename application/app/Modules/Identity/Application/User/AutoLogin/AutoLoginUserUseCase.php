<?php
declare(strict_types=1);

namespace App\Modules\Identity\Application\User\AutoLogin;

use App\Modules\Identity\Domain\Auth\AuthService;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserRepository;

final readonly class AutoLoginUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private AuthService $authService,
    ) {}

    public function execute(int $userId): array
    {
        $user = $this->userRepository->findById(new UserId($userId));

        if ($user === null) {
            throw new \DomainException("Пользователь #{$userId} не найден");
        }
        return $this->authService->autoLogin($user->getId()->getValue());
    }
}
