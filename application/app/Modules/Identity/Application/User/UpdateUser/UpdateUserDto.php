<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\User\UpdateUser;

readonly class UpdateUserDto
{
    public function __construct(
        public int     $userId,
        public ?string $name  = null,
        public ?string $email = null,
    ) {}
}
