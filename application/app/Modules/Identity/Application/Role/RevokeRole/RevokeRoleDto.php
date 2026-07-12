<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Role\RevokeRole;

readonly class RevokeRoleDto
{
    public function __construct(
        public int $userId,
        public int $roleId,
    ) {}
}
