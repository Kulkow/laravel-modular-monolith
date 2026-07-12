<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Seeders;

use App\Modules\Identity\Domain\Role\PredefinedRole;
use App\Modules\Identity\Infrastructure\Persistence\Role\RoleModel;
use Illuminate\Database\Seeder;


class RolesSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PredefinedRole::cases() as $predefinedRole) {
            RoleModel::firstOrCreate(
                ['name' => $predefinedRole->value],
                ['label' => $predefinedRole->label()],
            );
        }
    }
}
