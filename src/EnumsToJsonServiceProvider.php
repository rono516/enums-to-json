<?php

namespace rono516\EnumsToJson;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use rono516\EnumsToJson\Commands\EnumsToJsonCommand;

class EnumsToJsonServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('enums-to-json')
            ->hasConfigFile()
            ->hasCommand(EnumsToJsonCommand::class);
    }
}
