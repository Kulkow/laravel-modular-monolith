<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\User\ChangePassword;

readonly class ChangePasswordDto
{
    public function __construct(
        public int  $id,
        public string  $password
    ) {}
}
