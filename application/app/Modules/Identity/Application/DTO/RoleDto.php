<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\DTO;

readonly class RoleDto
{
    /** @param PermissionDto[] $permissions */
    public function __construct(
        public int    $id,
        public string $name,
        public string $label,
        public array  $permissions,
    ) {}
}
