<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Event;

final readonly class UserCreated
{
    public function __construct(
        public readonly ?int               $userId,
        public readonly string             $email,
        public readonly string             $name,
        public readonly \DateTimeImmutable $occurredAt,
    ) {}
}
