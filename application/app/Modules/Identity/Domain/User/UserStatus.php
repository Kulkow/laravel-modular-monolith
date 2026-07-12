<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\User;

final readonly class UserStatus
{
    const ACTIVE   = 1;
    const INACTIVE = 2;
    const BLOCKED  = 3;

    private string $name;

    public function __construct(private int $id)
    {
        $this->name = self::title($this->id);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isActive(): bool
    {
        return $this->id === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->id === self::INACTIVE;
    }

    public function isBlocked(): bool
    {
        return $this->id === self::BLOCKED;
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function inactive(): self
    {
        return new self(self::INACTIVE);
    }

    public static function blocked(): self
    {
        return new self(self::BLOCKED);
    }

    public static function statuses(): array
    {
        $map  = self::map();
        $list = [];
        foreach ($map as $id => $name) {
            $list[] = ['id' => $id, 'name' => $name];
        }
        return $list;
    }

    private static function title(int $id): string
    {
        $map = self::map();
        if (empty($map[$id])) {
            throw new \InvalidArgumentException("Unknown UserStatus: {$id}");
        }
        return $map[$id];
    }

    private static function map(): array
    {
        return [
            self::ACTIVE   => 'Активен',
            self::INACTIVE => 'Неактивен',
            self::BLOCKED  => 'Заблокирован',
        ];
    }
}
