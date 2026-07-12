<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Auth\GetCurrentUser;

use App\Modules\Identity\Application\DTO\RoleDto;

readonly class CurrentUserDto
{
    /**
     * @param RoleDto[] $roles
     * @param string[]  $permissions
     */
    public function __construct(
        public int    $id,
        public string $email,
        public string $name,
        public int    $statusId,
        public string $statusName,
        public array  $roles,
        public string $createdAt,
        public bool   $fullAccess,
        public array  $permissions,
    ) {}
}
