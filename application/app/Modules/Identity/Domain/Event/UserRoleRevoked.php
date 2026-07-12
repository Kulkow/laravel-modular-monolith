<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Event;

final readonly class UserRoleRevoked
{
    public function __construct(
        public readonly ?int               $userId,
        public readonly int                $roleId,
        public readonly \DateTimeImmutable $occurredAt,
    ) {}
}
