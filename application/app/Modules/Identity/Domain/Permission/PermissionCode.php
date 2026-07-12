<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Permission;

final readonly class PermissionCode
{
    public function __construct(private string $value)
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            throw new \InvalidArgumentException('Permission code cannot be empty');
        }
        if (!preg_match('/^[a-z][a-z0-9\-\.]*$/', $trimmed)) {
            throw new \InvalidArgumentException(
                "Invalid permission code format: '{$trimmed}'. Use lowercase letters, digits, hyphens and dots."
            );
        }
    }

    public static function fromEnum(\BackedEnum $enum): self
    {
        return new self((string) $enum->value);
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
