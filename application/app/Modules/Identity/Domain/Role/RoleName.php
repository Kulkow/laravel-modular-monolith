<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Role;

final readonly class RoleName
{
    public function __construct(private string $value)
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            throw new \InvalidArgumentException('Role name cannot be empty');
        }
        if (strlen($trimmed) > 125) {
            throw new \InvalidArgumentException('Role name cannot exceed 125 characters');
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

    public function __toString(): string
    {
        return $this->value;
    }
}
