<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Persistence\Permission;

use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @property int    $id
 * @property string $name
 * @property string $guard_name
 */
class PermissionModel extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
    ];

    protected $guard_name = 'sanctum';
}
