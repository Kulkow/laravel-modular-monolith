<?php

declare(strict_types=1);

namespace App\Modules\Identity\Tests\Unit\Domain\User;

use App\Modules\Identity\Domain\User\User;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserEmail;
use App\Modules\Identity\Domain\User\UserPassword;
use App\Modules\Identity\Domain\User\UserStatus;
use App\Modules\Identity\Domain\Role\Role;
use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Identity\Domain\Role\RoleName;
use App\Modules\Identity\Domain\Event\UserDeactivated;
use App\Modules\Identity\Domain\Event\UserRoleAssigned;
use App\Modules\Identity\Domain\Event\UserRoleRevoked;
use App\Modules\Identity\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

class UserTest extends TestCase
{
    #[Test]
    public function testCreateUserWithoutId(): void
    {
        $email = new UserEmail('test@example.com');
        $password = new UserPassword('SecurePass123!');

        $user = User::create($email, 'John Doe', $password);

        $this->assertNull($user->getId());
        $this->assertTrue($email->equals($user->getEmail()));
        $this->assertEquals('John Doe', $user->getName());
        $this->assertTrue($user->getStatus()->isActive());
        $this->assertEmpty($user->getRoles());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
        $this->assertEmpty($user->pullEvents());
    }

    #[Test]
    public function testRestoreUserWithAllData(): void
    {
        $id = new UserId(12);
        $email = new UserEmail('restored@example.com');
        $password = new UserPassword('OldPass123!');
        $status = UserStatus::blocked();

        $role1 = Role::restore(
            new RoleId(1),
            new RoleName('admin'),
            'Administrator',
            []
        );
        $role2 = Role::restore(
            new RoleId(2),
            new RoleName('user'),
            'Regular User',
            []
        );
        $roles = [$role1, $role2];

        $createdAt = new \DateTimeImmutable('2026-01-01 12:00:00');

        $user = User::restore($id, $email, 'Jane Doe', $password, $status, $roles, $createdAt);

        $this->assertEquals($id, $user->getId());
        $this->assertTrue($email->equals($user->getEmail()));
        $this->assertEquals('Jane Doe', $user->getName());
        $this->assertTrue($password->equals($user->getPassword()));
        $this->assertTrue($user->getStatus()->isBlocked());
        $this->assertCount(2, $user->getRoles());
        $this->assertEquals($createdAt, $user->getCreatedAt());
        $this->assertEmpty($user->pullEvents());
    }

    #[Test]
    public function testAssignRoleAndRecordEvent(): void
    {
        $user = $this->createActiveUser();
        $role = Role::restore(
            new RoleId(1),
            new RoleName('admin'),
            'Administrator',
            []
        );

        $user->assignRole($role);

        $this->assertTrue($user->hasRole($role->getId()));
        $this->assertCount(1, $user->getRoles());

        $events = $user->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRoleAssigned::class, $events[0]);
        $this->assertEquals($user->getId()->getValue(), $events[0]->userId);
        $this->assertEquals(1, $events[0]->roleId);
        $this->assertEquals('admin', $events[0]->roleName);
    }

    #[Test]
    public function testDoesNotAssignDuplicateRole(): void
    {
        $user = $this->createActiveUser();
        $role = Role::restore(
            new RoleId(1),
            new RoleName('admin'),
            'Administrator',
            []
        );

        $user->assignRole($role);
        $user->assignRole($role);

        $this->assertCount(1, $user->getRoles());
        $events = $user->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRoleAssigned::class, $events[0]);
    }

    #[Test]
    public function testRevokeRoleAndRecordEvent(): void
    {
        $user = $this->createActiveUser();
        $role = Role::restore(
            new RoleId(1),
            new RoleName('admin'),
            'Administrator',
            []
        );
        $user->assignRole($role);
        $user->pullEvents();

        $user->revokeRole(new RoleId(1));

        $this->assertFalse($user->hasRole(new RoleId(1)));
        $this->assertEmpty($user->getRoles());

        $events = $user->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRoleRevoked::class, $events[0]);
        $this->assertEquals($user->getId()->getValue(), $events[0]->userId);
        $this->assertEquals(1, $events[0]->roleId);
    }

    #[Test]
    public function testRevokeNonExistentRoleDoesNothing(): void
    {
        $user = $this->createActiveUser();
        $user->revokeRole(new RoleId(999));

        $this->assertEmpty($user->getRoles());
        $this->assertEmpty($user->pullEvents());
    }

    #[Test]
    public function testDeactivateUserAndRecordEvent(): void
    {
        $user = $this->createActiveUser();

        $user->deactivate();

        $this->assertTrue($user->getStatus()->isInactive());

        $events = $user->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserDeactivated::class, $events[0]);
        $this->assertEquals($user->getId()->getValue(), $events[0]->userId);
    }

    #[Test]
    public function testDeactivateAlreadyInactiveUserDoesNothing(): void
    {
        $user = $this->createActiveUser();
        $user->deactivate();
        $user->pullEvents();

        $user->deactivate();

        $this->assertTrue($user->getStatus()->isInactive());
        $this->assertEmpty($user->pullEvents());
    }

    #[Test]
    public function testBlockUser(): void
    {
        $user = $this->createActiveUser();
        $user->block();
        $this->assertTrue($user->getStatus()->isBlocked());
        $this->assertEmpty($user->pullEvents());
    }

    #[Test]
    public function testActivateUser(): void
    {
        $user = $this->createActiveUser();
        $user->deactivate();
        $user->activate();
        $this->assertTrue($user->getStatus()->isActive());
    }

    #[Test]
    public function testChangeName(): void
    {
        $user = $this->createActiveUser();
        $user->changeName('New Name');
        $this->assertEquals('New Name', $user->getName());
        $this->assertEmpty($user->pullEvents());
    }

    #[Test]
    public function testChangeEmail(): void
    {
        $user = $this->createActiveUser();
        $newEmail = new UserEmail('new@example.com');
        $user->changeEmail($newEmail);
        $this->assertTrue($newEmail->equals($user->getEmail()));
        $this->assertEmpty($user->pullEvents());
    }

    #[Test]
    public function testHasRole(): void
    {
        $user = $this->createActiveUser();
        $roleId = new RoleId(1);
        $role = Role::restore(
            $roleId,
            new RoleName('admin'),
            'Administrator',
            []
        );
        $user->assignRole($role);

        $this->assertTrue($user->hasRole($roleId));
        $this->assertFalse($user->hasRole(new RoleId(2)));
    }

    #[Test]
    public function testPullEventsClearsEventList(): void
    {
        $user = $this->createActiveUser();
        $role = Role::restore(
            new RoleId(1),
            new RoleName('admin'),
            'Administrator',
            []
        );
        $user->assignRole($role);
        $user->deactivate();

        $events = $user->pullEvents();
        $this->assertCount(2, $events);
        $this->assertInstanceOf(UserRoleAssigned::class, $events[0]);
        $this->assertInstanceOf(UserDeactivated::class, $events[1]);

        $this->assertEmpty($user->pullEvents());
    }

    #[Test]
    #[DataProvider('statusTransitionsProvider')]
    public function testStatusTransitions(UserStatus $initial, string $method, UserStatus $expected, bool $eventExpected): void
    {
        $user = $this->createUserWithStatus($initial);

        $user->{$method}();

        $this->assertTrue($user->getStatus()->equals($expected));

        $events = $user->pullEvents();
        if ($eventExpected) {
            $this->assertNotEmpty($events);
        } else {
            $this->assertEmpty($events);
        }
    }

    public static function statusTransitionsProvider(): array
    {
        return [
            'active -> deactivate' => [
                UserStatus::active(),
                'deactivate',
                UserStatus::inactive(),
                true,
            ],
            'inactive -> deactivate' => [
                UserStatus::inactive(),
                'deactivate',
                UserStatus::inactive(),
                false,
            ],
            'blocked -> deactivate' => [
                UserStatus::blocked(),
                'deactivate',
                UserStatus::blocked(),
                false,
            ],
            'inactive -> activate' => [
                UserStatus::inactive(),
                'activate',
                UserStatus::active(),
                false,
            ],
            'active -> block' => [
                UserStatus::active(),
                'block',
                UserStatus::blocked(),
                false,
            ],
            'blocked -> activate' => [
                UserStatus::blocked(),
                'activate',
                UserStatus::active(),
                false,
            ],
        ];
    }

    private function createActiveUser(): User
    {
        $id = new UserId(1);
        $email = new UserEmail('test@example.com');
        $password = new UserPassword('SecurePass123!');

        return User::restore(
            $id,
            $email,
            'Test User',
            $password,
            UserStatus::active(),
            [],
            new \DateTimeImmutable()
        );
    }

    private function createUserWithStatus(UserStatus $status): User
    {
        $id = new UserId(2);
        $email = new UserEmail('status@example.com');
        $password = new UserPassword('SecurePass123!');

        return User::restore(
            $id,
            $email,
            'Status User',
            $password,
            $status,
            [],
            new \DateTimeImmutable()
        );
    }
}
