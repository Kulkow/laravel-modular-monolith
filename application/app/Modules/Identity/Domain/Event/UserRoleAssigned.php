<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Event;

final readonly class UserRoleAssigned
{
    public function __construct(
        public readonly ?int               $userId,
        public readonly int                $roleId,
        public readonly string             $roleName,
        public readonly \DateTimeImmutable $occurredAt,
    ) {}
}
