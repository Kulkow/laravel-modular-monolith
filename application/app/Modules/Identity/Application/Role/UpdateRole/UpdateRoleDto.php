<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Role\UpdateRole;

final readonly class UpdateRoleDto
{
    public function __construct(
        public int     $roleId,
        public ?string $name,
        public ?string $label,
        public ?array  $permissionCodes,
    ) {}
}
