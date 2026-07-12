<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $modules = config('modules.modules', []);
        foreach ($modules as $module => $moduleConfig) {
            if(! is_array($moduleConfig)) {
                $module = $moduleConfig;
            }
            $provider = "App\\Modules\\{$module}\\Providers\\{$module}ServiceProvider";
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
