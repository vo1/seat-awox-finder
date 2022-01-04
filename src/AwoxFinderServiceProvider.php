<?php

namespace Vo1\Seat\AwoxFinder;

use Seat\Services\AbstractSeatPlugin;

class AwoxFinderServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        $this->addRoutes();
        $this->addViews();
        $this->addTranslations();

        $this->addMigrations();
    }

    /**
     * Include the routes.
     */
    public function addRoutes()
    {
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }
    }

    public function addTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'awox');
    }

    public function addViews()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'awox');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/awox.config.php', 'awox.config');

        $this->mergeConfigFrom(
            __DIR__ . '/Config/awox.sidebar.php',
            'package.sidebar'
        );

        $this->registerPermissions(
            __DIR__ . '/Config/awox.permissions.php', 'awox');
    }

    private function addMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    public function getName(): string
    {
        return 'AwoxFinder';
    }

    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/vo1/seat-awox-finder-discord';
    }

    public function getPackagistPackageName(): string
    {
        return 'vo1/seat-awox-finder-discord';
    }

    public function getPackagistVendorName(): string
    {
        return 'vo1';
    }
}