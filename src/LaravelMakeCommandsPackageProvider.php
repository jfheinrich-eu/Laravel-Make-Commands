<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use JfheinrichEu\LaravelMakeCommands\Providers\LaravelMakeCommandsServiceProvider;

class LaravelMakeCommandsPackageProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package->name('laravel-make-commands')
            ->hasConfigFile()
            ->publishesServiceProvider(LaravelMakeCommandsServiceProvider::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile()
                    ->copyAndRegisterServiceProviderInApp();
            });
    }

    public function packageBooted()
    {
        $commands = config('make-commands.commands', []);
        if ($commands !== []) {
            if ($this->app->runningInConsole()) {
                $this->commands(
                    commands: $commands,
                );
            }
        }
    }
}
