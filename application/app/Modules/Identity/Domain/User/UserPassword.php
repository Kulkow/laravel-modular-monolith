<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\User;

final readonly class UserPassword
{
    private const MIN_LENGTH = 8;

    public function __construct(private string $value)
    {
        if (mb_strlen($value) < self::MIN_LENGTH) {
            throw new \InvalidArgumentException(
                'Password must be at least ' . self::MIN_LENGTH . ' characters long.'
            );
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
