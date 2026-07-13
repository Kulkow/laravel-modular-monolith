<?php

declare(strict_types=1);

namespace App\Modules\Identity\Tests;

use App\Models\User;
use App\Modules\Identity\Domain\User\UserEmail;
use App\Modules\Identity\Domain\User\UserPassword;
use Tests\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    protected function createUser(array $attributes = []): User
    {
        return User::create(
            $attributes['email'] ?? new UserEmail('test@example.com'),
            $attributes['name'] ?? 'Test User',
            $attributes['password'] ?? new UserPassword('SecurePass123!')
        );
    }


    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
