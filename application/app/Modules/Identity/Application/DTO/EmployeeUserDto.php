<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\DTO;

readonly class EmployeeUserDto
{
    public function __construct(
        public int    $id,
        public string $fullName,
        public ?string $departmentName,
        public ?string $position,
    ) {}
}
