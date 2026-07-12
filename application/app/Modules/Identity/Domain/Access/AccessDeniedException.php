<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Access;

final class AccessDeniedException extends \RuntimeException
{
    public function __construct(
        private readonly string $permissionCode,
        private readonly int    $userId,
    ) {
        parent::__construct(
            "Доступ запрещён: пользователь #{$userId} не имеет права '{$permissionCode}'"
        );
    }

    public function getPermissionCode(): string
    {
        return $this->permissionCode;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
