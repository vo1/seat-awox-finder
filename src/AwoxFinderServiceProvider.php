<?php

namespace Vo1\Seat\AwoxFinder;

use Seat\Services\AbstractSeatPlugin;
use Vo1\Seat\AwoxFinder\Commands\Awox\Dispatch;
use Vo1\Seat\AwoxFinder\Commands\Awox\Find;

class AwoxFinderServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }
        $this->commands([ Dispatch::class, Find::class ]);
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'awox');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'awox');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/awox.config.php', 'awox.config');
        $this->mergeConfigFrom(__DIR__ . '/Config/awox.sidebar.php', 'package.sidebar');
        $this->mergeConfigFrom(__DIR__ . '/Config/awox.sidebar.settings.php', 'package.sidebar.settings.entries');
        $this->registerPermissions(__DIR__ . '/Config/awox.permissions.php', 'awox');
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'AwoxFinder';
    }

    /**
     * @inheritdoc
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/vo1/seat-awox-finder-discord';
    }

    /**
     * @inheritdoc
     */
    public function getPackagistPackageName(): string
    {
        return 'vo1/seat-awox-finder-discord';
    }

    /**
     * @inheritdoc
     */
    public function getPackagistVendorName(): string
    {
        return 'vo1';
    }
}