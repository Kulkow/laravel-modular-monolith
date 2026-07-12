<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Middleware;

use App\Modules\Identity\Domain\Access\AccessChecker;
use App\Modules\Identity\Domain\Access\Permissions\CtmPermission;
use App\Modules\Identity\Domain\Access\Permissions\IdentityPermission;
use App\Modules\Identity\Domain\Access\Permissions\OrderPermission;
use App\Modules\Identity\Domain\Access\Permissions\ProductionPermission;
use App\Modules\Identity\Domain\Access\Permissions\SupplyPermission;
use App\Modules\Identity\Domain\Access\Permissions\ToirPermission;
use App\Modules\Identity\Domain\Access\Permissions\WarehousePermission;
use App\Modules\Identity\Domain\User\UserId;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessCheckerMiddleware
{
    private const PERMISSION_CLASSES = [
        'toir' => ToirPermission::class,
        'ctm' => CtmPermission::class,
        'indentity' => IdentityPermission::class,
        'orders' => OrderPermission::class,
        'production' => ProductionPermission::class,
        'supply' => SupplyPermission::class,
        'warehouse' => WarehousePermission::class,
    ];

    public function __construct(
        private readonly AccessChecker $accessChecker,
    ) {}

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user->id) {
            throw new AccessDeniedHttpException();
        }

        $this->accessChecker->assertPermission(
            new UserId($user->id),
            $this->resolvePermission($permission),
        );

        return $next($request);
    }

    private function resolvePermission(string $value): \BackedEnum
    {
        $path = explode('.', $value);
        $module = array_shift($path);
        if(! empty(self::PERMISSION_CLASSES[$module])) {
            $class = self::PERMISSION_CLASSES[$module];
            $case = $class::tryFrom($value);
            if ($case !== null) {
                return $case;
            }
        }


        throw new \InvalidArgumentException("Unknown permission: {$value}");
    }
}
