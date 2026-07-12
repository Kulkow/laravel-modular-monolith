<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Auth\Logout;

use App\Modules\Identity\Domain\Auth\AuthService;

final readonly class LogoutUseCase
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function execute(): array
    {
        return $this->authService->logout();
    }
}
