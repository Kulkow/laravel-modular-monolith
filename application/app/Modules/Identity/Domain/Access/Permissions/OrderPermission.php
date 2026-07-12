<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Access\Permissions;

enum OrderPermission: string
{
    case View = 'orders.view';
    case Create = 'orders.create';
    case Update = 'orders.update';

    case ViewGoodsCards = 'orders.goods-cards.view';
    case ManageGoodsCards = 'orders.goods-cards.manage';

    case ViewContractors = 'orders.contractors.view';
    case ManageContractors = 'orders.contractors.manage';

    case ViewContracts = 'orders.contracts.view';
    case ManageContracts = 'orders.contracts.manage';

    public function label(): string
    {
        return match ($this) {
            self::View   => 'Просмотр заказов',
            self::Create => 'Создание заказов',
            self::Update => 'Редактирование заказов',
            self::ViewGoodsCards => 'Просмотр карточек товаров',
            self::ManageGoodsCards => 'Управление карточек товаров',
            self::ViewContractors => 'Просмотр контрагентов',
            self::ManageContractors => 'Управление контрагентами',
            self::ViewContracts => 'Просмотр договоров',
            self::ManageContracts => 'Управление договорами',
        };
    }

    public function group(): string
    {
        return 'Заказы';
    }
}
