<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\User\CreateUser;

readonly class CreateUserDto
{
    public function __construct(
        public string  $email,
        public string  $name,
        public string  $password,
        public ?array  $roleIds = null,
    ) {}
}
