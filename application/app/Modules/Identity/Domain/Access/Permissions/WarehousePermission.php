<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Access\Permissions;

enum WarehousePermission: string
{
     case View   = 'warehouse.view';
    case Manage = 'warehouse.manage';


    case CreateAcceptance  = 'warehouse.acceptance.create';
    case ConductedAcceptance = 'warehouse.acceptance.conducted';


    case CreateWriteOff  = 'warehouse.write-off.create';
    case ConductedWriteOff = 'warehouse.write-off.conducted';
    case CreateTransfer = 'warehouse.transfer.create';
    case ViewReports = 'warehouse.reports.view';

    public function label(): string
    {
        return match($this) {
            self::View   => 'Просмотр склада',
            self::Manage => 'Управление складом',
            self::CreateAcceptance  => 'Создание прихода',
            self::ConductedAcceptance  => 'Проведение прихода',
            self::CreateWriteOff  => 'Создание списания/расходования',
            self::ConductedWriteOff  => 'Проведение списания/расходования',
            self::ViewReports => 'Просмотр отчётов склада',
            self::CreateTransfer => 'Перемещение между складами',
        };
    }

    public function group(): string
    {
        return 'Склад';
    }
}
