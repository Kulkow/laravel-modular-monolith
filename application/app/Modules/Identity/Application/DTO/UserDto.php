<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\DTO;

readonly class UserDto
{
    /** @param RoleDto[] $roles */
    public function __construct(
        public int    $id,
        public string $email,
        public string $name,
        public int    $statusId,
        public string $statusName,
        public array  $roles,
        public string $createdAt
    ) {}
}
