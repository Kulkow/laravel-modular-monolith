<?php

namespace App\Modules\Identity\Tests\Fixtures;

use App\Modules\Identity\Domain\User\User;
use App\Modules\Identity\Domain\User\UserEmail;
use App\Modules\Identity\Domain\User\UserPassword;

class UserFixture
{
    public static function createActiveUser(): User
    {
        return User::create(
            new UserEmail('test@example.com'),
            'Test User',
            new UserPassword('SecurePass123!')
        );
    }
}
