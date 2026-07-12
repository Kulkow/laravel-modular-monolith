<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Auth\Login;

use App\Modules\Identity\Domain\Auth\AuthService;

final readonly class LoginUseCase
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function execute(array $credentials): array
    {
        return $this->authService->authenticate(
            email:    $credentials['email'] ?? '',
            password: $credentials['password'] ?? '',
        );
    }
}
