<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Auth;

interface AuthService
{
    /**
     * @return array{message: string, 'XSRF-TOKEN': string, laravel_session: string}
     * @throws \App\Exceptions\ValidationException
     */
    public function authenticate(string $email, string $password): array;
    public function autoLogin(int $userId): array;

    /** @return array{message: string} */
    public function logout(): array;
}
