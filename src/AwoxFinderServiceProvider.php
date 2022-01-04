<?php

namespace Vo1\Seat\AwoxFinder;

use Seat\Services\AbstractSeatPlugin;

class AwoxFinderServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
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