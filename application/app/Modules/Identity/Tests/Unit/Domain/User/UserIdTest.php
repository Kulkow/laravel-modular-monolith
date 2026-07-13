<?php

declare(strict_types=1);

namespace App\Modules\Identity\Tests\Unit\Domain\User;

use App\Modules\Identity\Domain\User\UserId;
use PHPUnit\Framework\TestCase;

class UserIdTest extends TestCase
{
    public function testCreateValidUserId(): void
    {
        $userId = new UserId(42);
        $this->assertEquals(42, $userId->getValue());
        $this->assertEquals('42', (string) $userId);
    }

    public function testThrowsExceptionForNonPositiveInteger(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('UserId must be a positive integer');

        new UserId(0);
    }

    public function testThrowsExceptionForNegativeInteger(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('UserId must be a positive integer');

        new UserId(-5);
    }

    public function testEqualsReturnsTrueForSameValue(): void
    {
        $id1 = new UserId(100);
        $id2 = new UserId(100);

        $this->assertTrue($id1->equals($id2));
        $this->assertTrue($id2->equals($id1));
    }

    public function testEqualsReturnsFalseForDifferentValues(): void
    {
        $id1 = new UserId(100);
        $id2 = new UserId(200);

        $this->assertFalse($id1->equals($id2));
        $this->assertFalse($id2->equals($id1));
    }

    public function testToStringReturnsStringRepresentation(): void
    {
        $userId = new UserId(123);
        $this->assertSame('123', $userId->__toString());
        $this->assertSame('123', (string) $userId);
    }

    public function testGetValueReturnsInt(): void
    {
        $userId = new UserId(999);
        $this->assertSame(999, $userId->getValue());
        $this->assertIsInt($userId->getValue());
    }
}
