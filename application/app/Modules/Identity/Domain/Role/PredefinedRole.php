<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Role;

use App\Modules\Identity\Domain\Access\Permissions\ProductionPermission;

enum PredefinedRole: string
{
    case SuperAdmin = 'super-admin';


    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin         => 'Администратор',
        };
    }


    /** @return \BackedEnum[] */
    public function permissions(): array
    {
        return match ($this) {
            self::SuperAdmin => [
                ProductionPermission::View,
            ],
        };
    }

    public function fullAllow(): bool
    {
        return match ($this) {
            self::SuperAdmin => true,
            default          => false,
        };
    }
}
