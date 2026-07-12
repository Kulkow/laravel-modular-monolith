<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Access\Permissions;

enum ProductionPermission: string
{
    // Общий доступ
    case View   = 'production.view';
    case Manage = 'production.manage';


    case ViewProductionOrders   = 'production.production-orders.view';
    case ViewProductionPassport  = 'production.production-passports.view';
    case RunProductionPassport  = 'production.production-passports.run';

    case ViewProductionLayout  = 'production.production-layout.view';
    case ManageProductionLayout  = 'production.production-layout.manage';

    case ViewProductionCut  = 'production.production-cut.view';
    case ManageProductionCut  = 'production.production-cut.manage';

    case ViewProductionAssembly  = 'production.production-assembly.view';
    case ManageProductionAssembly  = 'production.production-assembly.manage';
    case ViewProductionSewing  = 'production.production-sewing.view';
    case ManageProductionSewing  = 'production.production-sewing.manage';

    case ViewProductionStaff  = 'production.production-staff.view';
    case ManageProductionStaff  = 'production.production-staff.manage';
    case ViewProductionPackage  = 'production.production-package.view';
    case ManageProductionPackage  = 'production.production-package.manage';

    case ViewProductionDictionaries  = 'production.production-dictionaries.view';
    case ManageProductionDictionaries = 'production.production-dictionaries.manage';
    case ExecuteOperation = 'production.operations.execute';
    case CancelOperations = 'production.operations.cancel';

    // Нормирование
    case ManageNorms = 'production.norms.manage';
    case ViewNorms   = 'production.norms.view';

    // Отчёты
    case ViewReports = 'production.reports.view';
    case CreateReports = 'production.reports.create';

    public function label(): string
    {
        return match ($this) {
            self::View   => 'Просмотр производства',
            self::Manage => 'Управление производством',
            self::ViewProductionOrders   => 'Просмотр необработанных заказов',
            self::ViewProductionPassport  => 'Просмотр производственных паспортов',
            self::RunProductionPassport  => 'Запуск производственных паспортов',
            self::ViewProductionLayout  => 'Просмотр заданий на раскладку',
            self::ManageProductionLayout  => 'Управление  заданий на раскладку',
            self::ViewProductionCut  => 'Просмотр заданий на раскрой',
            self::ManageProductionCut  => 'Управление  заданиями на раскрой',
            self::ViewProductionAssembly  => 'Просмотр заданий на комплектовку',
            self::ManageProductionAssembly  => 'Управление  заданиями на комплектовку',
            self::ViewProductionPackage  => 'Просмотр заданий на упаковку',
            self::ManageProductionPackage  => 'Управление  заданиями на упаковку',
            self::ViewProductionSewing  => 'Просмотр заданий на пошив',
            self::ManageProductionSewing  => 'Управление  заданиями на пошив',
            self::ViewProductionStaff  => 'Просмотр персонала производства',
            self::ManageProductionStaff  => 'Управление персонала производства',
            self::ViewProductionDictionaries  => 'Просмотр справочников производства',
            self::ManageProductionDictionaries  => 'Управление справочниками производства',
            self::ExecuteOperation => 'Выполнение операций',
            self::CancelOperations => 'Отмена операциями',
            self::ManageNorms => 'Управление ценами производственной секунды',
            self::ViewNorms   => 'Просмотр цен производственной секунды',
            self::ViewReports => 'Просмотр отчётов производства',
            self::CreateReports => 'Выпуск документов производства',
        };
    }

    public function group(): string
    {
        return 'Производство';
    }
}
