<?php

namespace App\Modules\Core\Providers;

use App\Events\RegisterApiRoutes;
use App\Modules\Core\Domain\Repository\VatRateRepositoryInterface;
use App\Modules\Core\Infrastructure\Repository\Eloquent\VatRateRepository;
use App\Modules\Core\Listeners\RegisterCoreRoutes;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class CoreServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(
            app_path("Modules/Core/config/cache.php"),
            'cache.stores.core'
        );

    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(app_path("Modules/Core/database/migrations"));
        $this->loadRoutesFrom(app_path("Modules/Core/config/routes/web.php"));

    }
}
