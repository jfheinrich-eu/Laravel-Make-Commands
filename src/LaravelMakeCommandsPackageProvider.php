<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

class LaravelMakeCommandsPackageProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-make-commands')
            ->hasConfigFile()
            ->publishesServiceProvider('LaravelMakeCommandsServiceProvider')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile()
                    ->publishAssets();
            });

        $this->publishes([
            $this->package->basePath('/../stubs') => base_path("stubs/{$this->package->shortName()}"),
        ], "{$this->package->shortName()}-assets");

    }

    public function packageBooted(): void
    {
        $commands = config('make-commands.commands', []);
        if ($commands !== []) {
            if ($this->app->runningInConsole()) {
                $this->commands(
                    commands: $commands,
                );
            }
        }

        $versionFile = __DIR__ . '/../VERSION';
        if (File::exists($versionFile) && File::isReadable($versionFile)) {
            $version = File::get($versionFile);

            AboutCommand::add('Laravel Make Commands', fn () => ['Version' => $version]);
        }
    }
}
