<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Access\Permissions;

enum CtmPermission: string
{
    case ViewProductCards   = 'ctm.product-cards.view';
    case ManageProductCards = 'ctm.product-cards.manage';


    case ViewTechOperations   = 'ctm.tech-operations.view';
    case ManageTechOperations = 'ctm.tech-operations.manage';

    case ViewPatterns   = 'ctm.patterns.view';
    case ManagePatterns = 'ctm.patterns.manage';

    case ViewMaterials   = 'ctm.materials.view';
    case ManageMaterials = 'ctm.materials.manage';

    case ViewAccessories   = 'ctm.accessories.view';
    case ManageAccessories = 'ctm.accessories.manage';

    case ViewPackaging   = 'ctm.packaging.view';
    case ManagePackaging = 'ctm.packaging.manage';

    case ViewDictionaries   = 'ctm.dictionaries.view';
    case ManageDictionaries = 'ctm.dictionaries.manage';

    public function label(): string
    {
        return match ($this) {
            self::ViewProductCards   => 'Просмотр карточек изделий',
            self::ManageProductCards => 'Управление карточками изделий',
            self::ViewTechOperations   => 'Просмотр техопераций',
            self::ManageTechOperations => 'Управление техоперациями',
            self::ViewPatterns   => 'Просмотр лекал',
            self::ManagePatterns => 'Управление лекалами',
            self::ViewMaterials   => 'Просмотр материалов',
            self::ManageMaterials => 'Управление материалами',
            self::ViewAccessories   => 'Просмотр фурнитуры',
            self::ManageAccessories => 'Управление фурнитурой',
            self::ViewPackaging   => 'Просмотр упаковки',
            self::ManagePackaging => 'Управление упаковкой',
            self::ViewDictionaries   => 'Просмотр справочников',
            self::ManageDictionaries => 'Управление справочниками',
        };
    }

    public function group(): string
    {
        return 'Каталог (CTM)';
    }
}
