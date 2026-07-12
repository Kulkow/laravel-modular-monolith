<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Event;

final readonly class UserDeactivated
{
    public function __construct(
        public readonly ?int               $userId,
        public readonly \DateTimeImmutable $occurredAt,
    ) {}
}
