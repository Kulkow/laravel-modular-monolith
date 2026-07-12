<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Role\AssignRole;

readonly class AssignRoleByNameDto
{
    public function __construct(
        public string $userEmail,
        public string $roleName,
    ) {}
}
