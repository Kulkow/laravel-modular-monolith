<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Role\AssignRole;

readonly class AssignRoleDto
{
    public function __construct(
        public int $userId,
        public int $roleId,
    ) {}
}
