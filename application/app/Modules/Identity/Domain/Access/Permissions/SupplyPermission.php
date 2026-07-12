<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Access\Permissions;

enum SupplyPermission: string
{
    case View   = 'supply.view';
    case Manage = 'supply.manage';

    case ViewSupplyRequest  = 'supply.supply-requests.view';
    case ManageSupplyRequest = 'supply.supply-requests.manage';

    case ViewSupplyOrder  = 'supply.supply-orders.view';
    case ManageSupplyOrder = 'supply.supply-orders.manage';


    public function label(): string
    {
        return match ($this) {
            self::View   => 'Просмотр снабжения',
            self::Manage => 'Управление снабжением',

            self::ViewSupplyRequest  => 'Просмотр заявок в снабжение',
            self::ManageSupplyRequest => 'Управление заявками в снабжение',

            self::ViewSupplyOrder  => 'Просмотр заказов поставщикам',
            self::ManageSupplyOrder => 'Управление заказами поставщикам',
        };
    }

    public function group(): string
    {
        return 'Снабжение';
    }
}
