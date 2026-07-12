<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Persistence\Role;

use Spatie\Permission\Models\Role as SpatieRole;

/**
 * @property int    $id
 * @property string $name
 * @property string $label
 * @property string $guard_name
 */
class RoleModel extends SpatieRole
{
    protected $fillable = [
        'name',
        'label',
        'guard_name',
    ];

    protected $guard_name = 'sanctum';


}
