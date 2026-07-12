<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\DTO;

readonly class PermissionDto
{
    public function __construct(
        public int    $id,
        public string $code,
        public string $name,
        public string $group,
    ) {}
}
