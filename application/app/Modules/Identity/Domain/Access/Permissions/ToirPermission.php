<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Access\Permissions;

enum ToirPermission: string
{
    case View   = 'toir.view';

    public function label(): string
    {
        return match ($this) {
            self::View   => 'Доступ к ТОир',
        };
    }

    public function group(): string
    {
        return 'Техническое обслуживание и ремонт (ТОир)';
    }
}
